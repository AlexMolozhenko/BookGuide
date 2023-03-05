<button type="button" class="sort_by_surname">Sort by surname</button>
<div>
    <form>
        <label>Search by Name/Surname
            <input type="text" name="search_by" id="search_by_name_surname">
            <button type="button" class="button_search_by_name_surname">Search</button>
        </label>

    </form>

</div>
<table class="author_table table">
    <tr>
        <th class="num_table">№</th>
        <th class="name_author">Name</th>
        <th class="patronymic_author">Patronymic</th>
        <th class="surname_author">Surname</th>
        <th>Edit/Delete</th>
    </tr>
</table>
<div class="pagination_page">
        <form id="getAuthorListForm">
            <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>"
                   value="<?=Yii::$app->request->csrfToken?>"/>
        </form>
        <form>
            <input type="hidden" id="input_current_page">
            <input type="hidden" id="input_max_count_page">
        </form>
        <span><button type="button" class="back_page"><</button></span>
        <span class="current_page"></span>
        <span>/</span>
        <span class="max_count_page"></span>
        <span><button type="button" class="forward_page">></button></span>
</div>
