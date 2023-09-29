<?php declare(strict_types=1);

/*
 * This file is part of the nativebl package.
 *
 * (c) Banglalink Digital Communications Limited <info@banglalink.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NativeBL\Contracts\Service\CrudBoard;

use NativeBL\Services\CrudBoard\GridFilter;

/**
 * This interface defines blueprints of NativeBL CRUD grid.  
 * It will ensure to provide record for CrudBoard grid.
 *
 * @author Muhammad Abdullah Ibne Masud <abdullah.masud@banglalink.net>
 */

interface CrudGridInterface
{
    function getGridDataLoader(): CrudGridLoaderInterface; 
    function getGridData();
    function addColumns(array $columns);
    function getColumns();
    function addActions(array $actions = []);
    public function getFilter() : GridFilter;
    public static function init(CrudGridLoaderInterface $gridDataLoader, array $params): CrudGridInterface;
    
}