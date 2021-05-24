<tr>
    <th scope="row"><?=date("m/d/Y", strtotime($model->created_at))?></th>
    <td><? if($model->week){?> <?=sprintf("%02d",$model->week)?><? } ?></td>
    <td><? if($model->weight){?> <?=(int)$model->weight?> LBS <? } ?></td>
</tr>