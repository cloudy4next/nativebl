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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;
use NativeBL\Repository\AbstractNativeRepository;

class FaqRepository extends AbstractNativeRepository implements FaqRepositoryInterface, CrudGridLoaderInterface
{
    public function getModelFqcn(): string
    {
        return Faq::class;
    }

    public function getGridData() : iterable
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
        dd($id);
    }

    public function faqFilterData($filter)
    {
        return $query = $this->all();
    }

    public function store(Request $request)
    {
        if($request['id'] != null){
            $faq = $this->find($request['id']);
            $data = $request->except('_token');
            $faq->update($data);
        }
        else{
            return Faq::create($request->all());
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
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
