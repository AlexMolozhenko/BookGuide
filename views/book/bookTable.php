


<button type="button" class="sort_by_title">Sort by Title</button>
<div>
    <form>
        <label>Search by Title/Author
            <input type="text" name="search_by" id="search_by_title_author">
            <button type="button" class="button_search_by_title_author">Search</button>
        </label>

    </form>

</div>
<form id="getBookListForm">
    <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>"
           value="<?=Yii::$app->request->csrfToken?>"/>
</form>
<div class="listBook"></div>
<div class="pagination_page">
    <span><button type="button" class="back_page"><</button></span>
    <span class="current_page"></span>
    <span>/</span>
    <span class="max_count_page"></span>
    <span><button type="button" class="forward_page">></button></span>
    <form>
        <input type="hidden" id="input_current_page">
        <input type="hidden" id="input_max_count_page">
    </form>
</div>
