<!--Listing of Registered User-->
<div class="container mt-5">
<h4>List</h4>

<!--filters-->
<div class='mt-3 mb-3'>Filter<select onChange='rating(this.value)' class='form-select'>
    <option value='-1' selected readonly>Please choose Rating</option>
    <option value='1'>1</option>
    <option value='2'>2</option>
    <option value='3'>3</option>
    <option value='4'>4</option>
    <option value='5'>5</option>
  </select>
  <select onChange='latest(this.value)' class='form-select'>
    <option value='none' selected readonly>Please choose latest or oldest</option>
    <option value='latest'>Latest</option>
    <option value='oldest'>Oldest</option>
  </select>
</div>

<!--List of regisetered User -->
<div id="all-list" class="row">
  <?php
  foreach ($register_lists as $list) {
    _e('<div class="card" style="width: 18rem;">
    <div class="card-header">' . $list->firstname . ' ' . $list->lastname . '</div>
    <div class="card-body">
      <h6 class="card-title">Review :-</h6>
      <p>' . $list->review . '</p>
      <p>Rating :- ' . $list->rating . '</p>
    </div>
    <div class="card-footer">' . $list->email . '</div>
  </div>');
  }
  ?>
</div>
</div>
