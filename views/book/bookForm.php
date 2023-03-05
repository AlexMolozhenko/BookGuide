<div class="windowModal" style="display: none">
    <div class="closeBookForm btn btn-danger">❌</div>
    <div class="block_modal">
        <div class="bookFormMassage"></div>
        <form id="Book_form" enctype="multipart/form-data">
            <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>"
                   value="<?=Yii::$app->request->csrfToken?>"/>
            <input type="hidden" name="action" value="">
            <input type="hidden" name="book_id" value="">
            <label>Title :</label><input type="text" name="title" required="required"/>
            <label>Description :</label><textarea id="description" name="description"></textarea>
            <label>Book publication date :</label><input type="date" name="publication_date"/>
            <label>Author :</label><button type="button" class="getListAuthor btn btn-warning">AddAuthor</button>
            <div class="authors"></div>
            <div class="listA" data-action="false" ></div>
            <label>Photo :
                <div class="previewPhoto"></div>
            </label><input type="file" size="2" accept=".png,.jpg,.jpeg" id="photoInp" name="photo"/>
            <button type="button" class="saveBook btn btn-success">Save</button>
        </form>
    </div>
</div>

