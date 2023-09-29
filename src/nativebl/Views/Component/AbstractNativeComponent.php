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

use Illuminate\View\Component;
use  NativeBL\Contracts\Service\CrudBoard\CrudBoardInterface;


/**
 * this is a base component class of NativeBL platform. 
 *
 * @author Muhammad Abdullah Ibne Masud <abdullah.masud@banglalink.net>
 */

abstract class  AbstractNativeComponent extends Component 
{
   
     
    public function __construct(private CrudBoardInterface $crudBoard)
    {}

    public function getCrudBoard(): CrudBoardInterface
    {
        return $this->crudBoard;
    }
 
}