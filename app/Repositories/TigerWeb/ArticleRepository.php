<?php declare(strict_types=1);

/*
 * This file is part of the nativebl package.
 *
 * (c) Banglalink Digital Communications Limited <info@banglalink.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repositories\TigerWeb;

use App\Http\Requests\TigerWeb\ArticleRequest;
use App\Models\TigerWeb\ArticleReview;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use NativeBL\Repository\AbstractNativeRepository;
use App\Contracts\TigerWeb\ArticleRepositoryInterface;
use App\Models\TigerWeb\Article;
use App\Models\TigerWeb\TagKey;
use App\Models\TigerWeb\ArticleTag;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;


use Illuminate\Http\Request;

class ArticleRepository extends AbstractNativeRepository implements ArticleRepositoryInterface, CrudGridLoaderInterface
{

    public function getModelFqcn(): string
    {
        return Article::class;
    }

    public function getGridData(array $filters = []): iterable
    {
        $query = Article::join('users', 'articles.created_by', '=', 'users.id')
            ->join('article_categories', 'articles.article_category_id', '=', 'article_categories.id')
            ->leftJoin('users as u', 'articles.updated_by', '=', 'u.id')
            ->leftJoin('articles as ac', 'articles.ref_id', '=', 'ac.id')
            // ->whereRaw("articles.end_date IS NULL")
            // ->orWhere('articles.end_date', '>', \DB::raw('NOW()'))
            // ->orWhere('articles.review_status', '=', 'ARCHIVED')
            ->get(['articles.*', 'users.email as createdBy', 'u.email as updatedBy', 'article_categories.title as articleCategoryTitle', 'ac.title as parentArticle']);
        return $query;
    }

    public function details($id)
    {
        // dd($articleDetails);
        return Article::with([
            'articleCategory',
            'articleReview',
            'articleTag.tagKey',
            'parentArticle',
            'parentTree',
            'articleFaq',
            'updatedByUser'
        ])->where('id', $id)->first();
    }

    public function slugDetails($slug)
    {
        return Article::with([
            'articleCategory',
            'articleReview',
            'articleTag.tagKey',
            'parentArticle',
            'parentTree',
            'articleFaq',
            'updatedByUser'
        ])->where('slug', $slug)->firstOrFail();
    }

    public function applyFilterData(Collection $data, array $filters): Collection
    {
        foreach ($filters as $field => $value) {
            if ($value !== null) {
                $data = $data->filter(function ($item) use ($field, $value) {
                    return $item[$field] !== null && stripos($item[$field], $value) !== false;
                });
            }
        }
        return $data;

    }

    public function articleFilterData($filter)
    {
        dd($filter);
        $query = Article::query();
        if (isset($filter['search_text']) && $filter['search_text']) {
            $query->where('title', 'like', "%{$filter['search_text']}%");
            $query->orWhere('slug', 'like', "%{$filter['search_text']}%");
            $query->orWhere('content', 'like', "%{$filter['search_text']}%");

        }

        if (isset($filter['article_category_id']) && $filter['article_category_id']) {
            $query->where('article_category_id', '=', $filter['article_category_id']);
        }

        return $query;
    }

    public function getRecordForEdit(int|string $id): object
    {
        // dd($article);
        return Article::query()
            ->join('article_tags', 'articles.id', '=', 'article_tags.article_id')
            ->join('tag_keys', 'article_tags.tag_key_id', '=', 'tag_keys.id')
            ->where('articles.id', $id)
            ->get(['articles.*', 'tag_keys.title as tag_name'])
            ->first();
        // return $this->find($id);
    }


    public function store_old(Request $request)
    {
        // dd($request);
        if ($request['id'] != null) {
            $articleId = $request['id'];

            try {
                DB::beginTransaction();

                $filePath = '';

                if ($request->hasFile('file')) {
                    $currentDate = Carbon::now()->format('d-m-Y');
                    $destinationPath = 'assets/article/' . $currentDate;
                    $filePath = fileUpload($request->file('file'), $destinationPath);
                }

                $requestData = $request->all();

                $requestData['image'] = $filePath;
                $tmpImagePath = '';

                if (isset($request['correction']) && ($request['correction'] == 'submit')) {
                    // dd($request->all());
                    $prevData = $this->find($request['id']);
                    $prevData->end_date = date('Y-m-d h:i:s');
                    $prevData->updated_by = $request['created_by'];
                    $tmpImagePath = $prevData['image'];
                    $prevData->save();


                    $requestData['ref_id'] = $request['id'];
                    $requestData['id'] = NULL;
                    if ($request->hasFile('file')) {
                        $requestData['image'] = $filePath;
                    } else {
                        $requestData['image'] = $tmpImagePath;
                    }
                    $articleId = Article::create($requestData)->id;
                } else {
                    $prevData = $this->find($request['id']);
                    $prevData->title = $request['title'];
                    $prevData->slug = $request['slug'];
                    $prevData->article_category_id = $request['article_category_id'];
                    $prevData->content = $request['content'];
                    $prevData->service_manager = $request['service_manager'];
                    $prevData->call_disposition_code = $request['call_disposition_code'];
                    $prevData->link_redirection = $request['link_redirection'];
                    $prevData->complaint_path = $request['complaint_path'];
                    $prevData->review_status = $request['review_status'];
                    $prevData->start_date = $request['start_date'];
                    $prevData->end_date = $request['end_date'];
                    $prevData->updated_by = $request['created_by'];

                    if ($request->hasFile('file')) {
                        $prevData->image = $filePath;
                    } else {
                        $prevData->image = $prevData['image'];
                    }

                    $prevData->save();
                }


                ArticleTag::where('article_id', $articleId)->delete();

                $tagData = TagKey::where('title', $request['tag_name'])->first();
                // dd($tagData);
                if ($tagData == null) {
                    $tagKey = new TagKey();
                    $tagKey->title = $request['tag_name'];
                    $tagKey->slug = str_replace(' ', '-', $request['tag_name']);
                    $tagKey->save();
                    $tagId = $tagKey->id;

                    $articleTag = new ArticleTag();
                    $articleTag->tag_key_id = $tagId;
                    $articleTag->article_id = $articleId;
                    $articleTag->save();
                } else {
                    $articleTag = new ArticleTag();
                    $articleTag->tag_key_id = $tagData['id'];
                    $articleTag->article_id = $articleId;
                    $articleTag->save();
                }


                DB::commit();
                return 1;
            } catch (\Exception $e) {
                DB::rollBack();
                return $e->getMessage();

            }


        } else {

            try {

                DB::beginTransaction();
                $filePath = '';

                if ($request->hasFile('file')) {
                    $currentDate = Carbon::now()->format('d-m-Y');
                    $destinationPath = 'assets/article/' . $currentDate;
                    $filePath = fileUpload($request->file('file'), $destinationPath);
                }

                $requestData = $request->all();

                $requestData['image'] = $filePath;

                unset($requestData['id']);

                $articleId = Article::create($requestData)->id;

                $tagData = TagKey::where('title', $request['tag_name'])->first();
                // dd($tagData);
                if ($tagData == null) {
                    $tagKey = new TagKey();
                    $tagKey->title = $request['tag_name'];
                    $tagKey->slug = str_replace(' ', '-', $request['tag_name']);
                    $tagKey->save();
                    $tagId = $tagKey->id;

                    $articleTag = new ArticleTag();
                    $articleTag->tag_key_id = $tagId;
                    $articleTag->article_id = $articleId;
                    $articleTag->save();
                } else {
                    $articleTag = new ArticleTag();
                    $articleTag->tag_key_id = $tagData['id'];
                    $articleTag->article_id = $articleId;
                    $articleTag->save();
                }

                DB::commit();
                return 1;

            } catch (\Exception $e) {
                DB::rollBack();
                return $e->getMessage();

            }


            // return Article::create($request->all());
        }
    }


// Assuming this is within a controller class and necessary models and helpers are imported.

    public function store(ArticleRequest $request)
    {
        DB::beginTransaction();

        try {
            $articleId = $request->input('id');
            $filePath = $this->handleFileUpload($request);

            if ($articleId) {
                $article = $this->findAndUpdateArticle($request, $filePath, $articleId);
            } else {
                $article = $this->createNewArticle($request, $filePath);
            }

            $this->handleArticleTags($request, $article->id);

            DB::commit();
            return 1;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    /**
     * @throws \Exception
     */
    private function handleFileUpload(ArticleRequest $request)
    {
        if ($request->hasFile('file')) {
            $currentDate = Carbon::now()->format('d-m-Y');
            $destinationPath = 'assets/article/' . $currentDate;
            return fileUpload($request->file('file'), $destinationPath);
        }
        return '';
    }

    private function findAndUpdateArticle(ArticleRequest $request, $filePath, $articleId)
    {
        $article = Article::findOrFail($articleId);
        $requestData = $request->all();
        $requestData['image'] = $filePath ?: $article->image;

        if (isset($requestData['correction']) && $requestData['correction'] == 'submit') {
            $article->end_date = now();
            $article->updated_by = $requestData['created_by'];
            $requestData['slug'] = $article->slug;
            $article->slug = Str::slug($article->slug . '-archive-' . $article->id);
            $article->save();

            $requestData['ref_id'] = $articleId;
            unset($requestData['id']);
            session()->flash('success', 'New Version of Article has been created successfully !');
            return Article::create($requestData);
        } else {
            $this->updateArticleAttributes($article, $requestData);
            session()->flash('success', 'Article has been updated successfully !');
            return $article;
        }
    }

    private function createNewArticle(ArticleRequest $request, $filePath)
    {
        $requestData = $request->except('id');
        $requestData['image'] = $filePath;
        session()->flash('success',  'New Article has been created successfully !');
        return Article::create($requestData);
    }

    private function updateArticleAttributes($article, $requestData)
    {
        unset($requestData['slug']);
        $article->fill($requestData);
        $article->save();
    }

    private function handleArticleTags(ArticleRequest $request, $articleId)
    {
        ArticleTag::where('article_id', $articleId)->delete();
        $tagNames = explode(',', $request->input('tag_name')); // Assuming tag names are separated by commas

        foreach ($tagNames as $tagName) {
            $tagData = TagKey::firstOrCreate(
                ['title' => $tagName],
                ['slug' => Str::slug($tagName)]
            );
            ArticleTag::create([
                'tag_key_id' => $tagData->id,
                'article_id' => $articleId
            ]);
        }
    }


    public function article_review_submit(Request $request)
    {
        try {
            DB::beginTransaction();

            $article_review = new ArticleReview();
            $article_review->article_id = $request->article_id;
            $article_review->review_status = $request->review_status;
            $article_review->review_comments = $request->review_comments;
            $article_review->created_at = Carbon::now()->format('Y-m-d H:i:s');
            $article_review->created_by = Auth::user()->id;
            $article_review->save();

            DB::commit();
            return 1;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();

        }
    }

    public function articleArchive($id)
    {
        try {
            DB::beginTransaction();

            $article = Article::find($id);
            $article->review_status = "ARCHIVED";
            $article->updated_by = Auth::user()->id;
            $article->end_date = Carbon::now()->format('Y-m-d H:i:s');
            $article->save();

            DB::commit();
            return 1;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();

        }
    }


}
