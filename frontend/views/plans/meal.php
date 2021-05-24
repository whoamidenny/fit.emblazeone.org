<?php
$bundle=\frontend\assets\AppAsset::register($this);

$this->registerJs('
        $(document).on("click", "[data-tag-id]", function(){
            if($(this).data("tag-selected")) $(this).data("tag-selected","");
            else $(this).data("tag-selected","true");
            $(this).toggleClass("active");
        
            reloadPjax();
            
            return false;
        });
        
        function reloadPjax(){
            var tags=[];
            $("[data-tag-id]").each(function(){
                if($(this).data("tag-selected")) tags.push($(this).data("tag-id"));
            });
            
            $.pjax.reload({
                container:"#workout", 
                url: "'.\yii\helpers\Url::to(['plans/meal']).'",  
                data: {
                    search: $("#search input[type=text]").val(), 
                    tags:tags
                }
            });
        }
        
        $("#search").submit(function(){
            reloadPjax();
            
            return false;
        });
    ', \yii\web\View::POS_END);
?>

<section class="dashboard Meal_bg">
    <div class="container">

        <div class="row">
            <div class="col-sm-12">
                <div class="mb-4 d-flex flex-wrap justify-content-between align-items-center">
                    <div class="weight_2">Meal Plans</div>
                    <!--<a href="#" class="btn-all">View All</a>-->
                </div>
            </div>
            <div class="col-sm-12 mb-4">
                <div class="mb-4 d-flex flex-wrap justify-content-between align-items-center">
                    <div class="brows">Browse By</div>
                </div>
                <? foreach ($tags as $tag){ ?>
                    <a href="<?=\yii\helpers\Url::to(['videos/exercises','tags[]'=>$tag->id])?>" data-tag-selected="<?=$tag->selected?'true':''?>" data-tag-id="<?=$tag->id?>" class="categorries <?=$tag->selected?'active':''?>"><?=$tag->title?></a>
                <? } ?>
            </div>

            <? \yii\widgets\Pjax::begin(['id'=>'workout'])?>
                <div class="row">

                    <? foreach ($categories as $category){
                        echo $this->render('meals-category',[
                            'plans'=>$category->getPlans($searchModel->tags, $searchModel->search)->all(),
                            'category'=>$category
                        ]);
                    } ?>

                </div>
            <? \yii\widgets\Pjax::end(); ?>

        </div>
    </div>
</section>
