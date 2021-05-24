<tr>
    <th scope="row"><?=date("m/d/Y", strtotime($model->created_at))?></th>
    <td  class="text-center"><? if($model->weight){?> <?=(int)$model->weight?> LBS <? } ?></td>
    <td class="text-center">
        <div class="for_foto">
            <a href="<?=$model->getImage(\backend\modules\clients\models\ClientPhotoTracker::$imageTypes[0])->getUrl()?>"><img src="<?=$model->getImage(\backend\modules\clients\models\ClientPhotoTracker::$imageTypes[0])->getUrl('x100')?>" alt="" /></a>
        </div>
    </td>
    <td class="text-center">
        <div class="for_foto">
            <a href="<?=$model->getImage(\backend\modules\clients\models\ClientPhotoTracker::$imageTypes[1])->getUrl()?>"><img src="<?=$model->getImage(\backend\modules\clients\models\ClientPhotoTracker::$imageTypes[1])->getUrl('x100')?>" alt="" /></a>
        </div>
    </td>
    <td class="text-center">
        <div class="for_foto">
            <a href="<?=$model->getImage(\backend\modules\clients\models\ClientPhotoTracker::$imageTypes[2])->getUrl()?>"><img src="<?=$model->getImage(\backend\modules\clients\models\ClientPhotoTracker::$imageTypes[2])->getUrl('x100')?>" alt="" /></a>
        </div>
    </td>
</tr>