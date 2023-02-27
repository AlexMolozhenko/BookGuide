<div class="windowModal" style="display: none">
    <div class="closeAuthorForm btn btn-danger">❌</div>
    <div class="modalNewAuthor">
        <div class="authorFormMassage"></div>
        <form id="author_form" name="author_form" enctype="application/x-www-form-urlencoded"  class="author_form">
            <input type="hidden" name="id"/>
            <label>Name
                <input type="text" name="name" maxlength="50">
            </label>
            <label>Patronymic
                <input type="text" name="patronymic" maxlength="50">
            </label>
            <label>Surname
                <input type="text" name="surname" minlength="3" maxlength="50">
            </label>
            <button type="button" class="btn btn-success saveAuth" >Save</button>
        </form>
    </div>
</div>
