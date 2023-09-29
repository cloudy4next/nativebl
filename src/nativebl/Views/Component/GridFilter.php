<?php declare(strict_types=1);

/*
 * This file is part of the nativebl package.
 *
 * (c) Banglalink Digital Communications Limited <info@banglalink.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NativeBL\Views\Component;

use NativeBL\Services\CrudBoard\GridFilter as Filter;

/**
 * this is a component class responsible for Crud Grid view
 *
 * @author Muhammad Abdullah Ibne Masud <abdullah.masud@banglalink.net>
 */

class GridFilter extends AbstractNativeComponent
{

    public Filter $filter;    
   /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        $this->prepareFields();
        return view('native::crudboard.filter');
    }

    private function prepareFields(): void 
    {
        $filter = $this->getCrudBoard()
                ->getGrid()->getFilter();
        $fields = $filter->getFields();
        foreach($fields as $name => $field) {
            $field->setName(Filter::CONTAINER_NAME."[$name]");
        }
        $this->filter = $filter;
    }
}