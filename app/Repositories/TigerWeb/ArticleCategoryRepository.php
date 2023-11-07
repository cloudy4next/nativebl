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


use NativeBL\Repository\AbstractNativeRepository;
use App\Contracts\TigerWeb\ArticleCategoryRepositoryInterface;
use App\Models\TigerWeb\ArticleCategory;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

use Illuminate\Http\Request;


class ArticleCategoryRepository extends AbstractNativeRepository implements ArticleCategoryRepositoryInterface, CrudGridLoaderInterface
{
    public function getModelFqcn(): string
    {
        return ArticleCategory::class;
    }

    public function getGridData(array $filters = []): ?iterable
    {
        // $with = ['parentCategory', 'createdBy', 'updatedBy'];
        // $query = ArticleCategory::query();
        $query = ArticleCategory::join('users', 'article_categories.created_by', '=', 'users.id')
            ->leftJoin('article_categories as ac', 'article_categories.ref_id', '=', 'ac.id')
            ->leftJoin('users as u', 'article_categories.updated_by', '=', 'u.id')
            ->get(['article_categories.*', 'users.email as createdBy', 'u.email as updatedBy', 'ac.title as parentCategory']);
        // if (isset($filter['search_text']) && $filter['search_text']) {
        //     $query->where('title', 'like', "%{$filter['search_text']}%");
        //     $query->orWhere('slug', 'like', "%{$filter['search_text']}%");
        //     $query->orWhere('service_type', 'like', "%{$filter['search_text']}%");
        //     $query->orWhere('description', 'like', "%{$filter['search_text']}%");
        // }

        // $query->with($with);
        // dd($query->get());
        // dd($query);
        return $query;
    }


    public function store(Request $request)
    {
        // dd($request);
        if ($request['id'] != null) {

            $prevCategory = $this->find($request['id']);
            $prevCategory['end_date'] = date('Y-m-d h:i:s');
            $prevCategory['updated_by'] = $request['created_by'];
            $prevCategory->save();

            $request['ref_id'] = $request['id'];
            $request['id'] = NULL;
            try {
                ArticleCategory::create($request->all());
                return 1;
            } catch (\Exception $e) {
                return $e->getMessage();

            }
        } else {
            try {
                ArticleCategory::create($request->all());
                return 1;
            } catch (\Exception $e) {
                return $e->getMessage();

            }
        }
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

    public function categoryArchive($id)
    {
        try {
            DB::beginTransaction();

            $article = ArticleCategory::find($id);
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



    public function articleCategoryFilterData($filter)
    {
        //return $query = $this->all();
        $query = ArticleCategory::query();
        if (isset($filter['search_text']) && $filter['search_text']) {
            $query->where('title', 'like', "%{$filter['search_text']}%");
            $query->orWhere('slug', 'like', "%{$filter['search_text']}%");
            $query->orWhere('service_type', 'like', "%{$filter['search_text']}%");
            $query->orWhere('description', 'like', "%{$filter['search_text']}%");

        }

        return $query;
    }



    public function updateArticleCategory($id, $data)
    {
        // dd($articleCategory);

        // $articleReview = ArticleCategory::create($data);
        $articleCategoryNew = new ArticleCategory();
        $articleCategoryNew->title = $data['title'];
        $articleCategoryNew->slug = $data['slug'];
        $articleCategoryNew->service_type = $data['service_type'];
        $articleCategoryNew->description = $data['description'];
        $articleCategoryNew->ref_id = $id;
        $articleCategoryNew->created_by = $data['created_by'];
        $articleCategoryNew->start_date = $data['start_date'];
        $articleCategoryNew->created_at = $data['created_at'];
        $articleCategoryNew = $articleCategoryNew->save();
        return $articleCategoryNew;
    }


}
