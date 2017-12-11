<!-- Block Custom module -->

{block name='page_content'}

<div class="home_categories blockPosition hidden-xs  text-xs-center p-y-2">
  <div class="container-fluid">
    {if isset($categories) AND $categories}
    <div class="d-flex flex-row">
        {foreach from=$categories item=category name=homeCategories}
            {assign var='categoryLink' value=$link->getcategoryLink($category.id_category, $category.link_rewrite)}
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 
                {if $smarty.foreach.homeCategories.first}first_item{elseif $smarty.foreach.homeCategories.last}last_item{else}item{/if}">
                    <a href="{$categoryLink}" title="{$categoryLink}" class="category_image"> 
                    <img img="img-responsive" src="{$img_cat_dir}{$category.id_category}-small_default.jpg" alt="{$category.name|truncate:35}" title="{$category.name}" 
                class="categoryImage"   />
                    </a>
                <a href="{$categoryLink}" title="{$category.name}">{$category.name|truncate:35}</a>
            </div>
        {/foreach}
       
    </div>
    {else}
        <p>{l s='No categories' mod='ps_showcategory '}</p>
    {/if}
    <div class="cr"><p> </p></div>
  </div>
</div>
{/block}
<!-- /Block Custom module -->
