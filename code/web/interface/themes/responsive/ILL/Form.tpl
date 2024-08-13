<form id="NewPatronRequest" method="post" action="/ILL/AJAX?method=postFormData" class="form form-horizontal">
  <h1>ILL Request Form</h1>
  <div class="col-tn-12 col-xs-12 col-sm-8 col-md-9 col-lg-9" id="main-content-with-sidebar">
    <div class="form-group propertyRow"><label for="article_title">Article Title</label>
      <input type=text name="article_title" id="article_title" />
    </div>
    <div class="form-group propertyRow"><label for="associated_id">Associated Id</label>
      <input type=text name="associated_id" id="associated_id" />
    </div>
    <div class="form-group propertyRow"><label for="author">Author</label>
      <input type=text name="author" id="author" />
    </div>
    <div class="form-group propertyRow"><label for="issn">Issn</label>
      <input type=text name="issn" id="issn" />
    </div>
    <div class="form-group propertyRow"><label for="issue">Issue</label>
      <input type=text name="issue" id="issue" />
    </div>
    <div class="form-group propertyRow"><label for="pages">pages</label>
      <input type=text name="pages" id="pages" />
    </div>
    <div class="form-group propertyRow"><label for="publisher">publisher</label>
      <input type=text name="publisher" id="publisher" />
    </div>
    <div class="form-group propertyRow"><label for="pubmedid">pubmedid</label>
      <input type=text name="pubmedid" id="pubmedid" />
    </div>
    <div class="form-group propertyRow"><label for="title">title</label>
      <input type=text name="title" id="title" />
    </div>
    <div class="form-group propertyRow"><label for="volume">volume</label>
      <input type=text name="volume" id="volume" />
    </div>
    <div class="form-group propertyRow"><label for="year">year</label>
      <input type=text name="year" id="year" />
    </div>
    <button type="submit">Submit</button>
  </div>
</form>