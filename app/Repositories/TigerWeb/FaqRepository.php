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

use App\Contracts\TigerWeb\FaqRepositoryInterface;
use App\Models\TigerWeb\Faq;
use App\Models\TigerWeb\FaqTag;
use App\Models\TigerWeb\TagKey;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;
use NativeBL\Repository\AbstractNativeRepository;
use Illuminate\Support\Collection;
class FaqRepository extends AbstractNativeRepository implements FaqRepositoryInterface, CrudGridLoaderInterface
{
    public function getModelFqcn(): string
    {
        return Faq::class;
    }

    public function getGridData(array $filters=[]): ?iterable
    {
        $faq_type = \Request::segment(2);
        $faq_type_id = \Request::segment(3);
        $query = Faq::query()
            ->where('faqs.faq_type',$faq_type)
            ->where('faqs.ref_id', $faq_type_id)
            ->join('articles', 'faqs.ref_id', '=', 'articles.id')
            ->get(['faqs.*', 'articles.title as Title']);
        return $query;
    }

    public function details($id)
    {
        return Faq::query()
            ->where('id',$id)
            ->get();
    }

    public function faqFilterData($filter)
    {
        return $query = $this->all();
    }

    public function store(Request $request)
    {
        if($request['id'] != null){ // update
            $faqId = $request['id'];

            try {
                DB::beginTransaction();
                $faq = $this->find($request['id']);
                $data = $request->except('_token');
                $faq->update($data);

                if($request['tag_name'] != null){
                    FaqTag::where('faq_id', $faqId)->delete();

                    $tagData = TagKey::where('title', $request['tag_name'])->first();

                    if($tagData == null){
                        $tagKey = new TagKey();
                        $tagKey->title = $request['tag_name'];
                        $tagKey->slug = str_replace(' ', '-', $request['tag_name']);
                        $tagKey->save();
                        $tagId = $tagKey->id;

                        $faqTag = new FaqTag();
                        $faqTag->tag_key_id = $tagId;
                        $faqTag->faq_id = $faqId;
                        $faqTag->save();
                    }
                    else{
                        $faqTag = new FaqTag();
                        $faqTag->tag_key_id = $tagData['id'];
                        $faqTag->faq_id = $faqId;
                        $faqTag->save();
                    }
                }

                DB::commit();
                return 1;
            }
            catch (\Exception $e) {
                DB::rollBack();
                return $e->getMessage();

            }
        }
        else{ // store
            try {
                DB::beginTransaction();
                $faqId = Faq::create($request->all())->id;
                $tagData = TagKey::where('title', $request['tag_name'])->first();
                if($tagData == null){ // new tag key so create both tag_key & faq_tag
                    $tagKey = new TagKey();
                    $tagKey->title = $request['tag_name'];
                    $tagKey->slug = str_replace(' ', '-', $request['tag_name']);
                    $tagKey->save();
                    $tagId = $tagKey->id;

                    $faqTag = new FaqTag();
                    $faqTag->tag_key_id = $tagId;
                    $faqTag->faq_id = $faqId;
                    $faqTag->save();
                }
                else{ // create only faq tag
                    $faqTag = new FaqTag();
                    $faqTag->tag_key_id = $tagData['id'];
                    $faqTag->faq_id = $faqId;
                    $faqTag->save();
                }
                DB::commit();
                return 1;

            }
            catch (\Exception $e) {
                DB::rollBack();
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

    public function getRecordForEdit(int|string $id) : object
    {
       $faq = Faq::query()
           ->join('faq_tags', 'faqs.id', '=', 'faq_tags.faq_id')
           ->join('tag_keys', 'faq_tags.tag_key_id', '=', 'tag_keys.id')
           ->where('faqs.id', $id)
           ->get(['faqs.*', 'tag_keys.title as tag_name'])
           ->first();
// dd($faq);
       return $faq;
        // return $this->find($id);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            FaqTag::where('faq_id', $id)->delete();
            Faq::where('id', $id)->delete();

            DB::commit();
            return 1;

        }
        catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();

        }
    }
}
