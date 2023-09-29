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

use NativeBL\Services\CrudBoard\CrudShow as Show;

/**
 * this is a component class responsible for Crud Grid view
 *
 * @author Muhammad Abdullah Ibne Masud <abdullah.masud@banglalink.net>
 */

class CrudShow extends AbstractNativeComponent
{
   
    public Show $show;

   /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        $this->show = $this->getCrudBoard()->getCrudShow();
        return view('native::crudboard.show');
    }
}