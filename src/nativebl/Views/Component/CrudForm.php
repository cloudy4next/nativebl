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

use NativeBL\Form\CrudForm as Form;

/**
 * this is a component class responsible for Crud Grid view
 *
 * @author Muhammad Abdullah Ibne Masud <abdullah.masud@banglalink.net>
 */

class CrudForm extends AbstractNativeComponent
{
   public Form $form;
   /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        $this->form = $this->getCrudBoard()->getForm();
        return view('native::crudboard.form');
    }
}