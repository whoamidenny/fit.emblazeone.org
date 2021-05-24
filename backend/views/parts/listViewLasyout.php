<!--<div class="row">-->
<!--    <div class="col-md-5">-->
<!--        {summary}-->
<!--    </div>-->
<!--    <div class="col-md-7">-->
<!--        <div class="dataTables_paginate paging_simple_numbers">-->
<!--            {pager}-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<table id="sortable" class="table table-hover nowrap js-table-sortable ui-sortable">
    <thead>
    <tr>
        <?=$columns?>
    </tr>
    </thead>
    <tbody>
    {items}
    </tbody>
</table>
<div class="row">
    <div class="col-md-5">
        {summary}
    </div>
    <div class="col-md-7">
        <div class="dataTables_paginate paging_simple_numbers">
            {pager}
        </div>
    </div>
</div>