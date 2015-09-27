{{ content() }}
<div class='page-header'>
    <h2>Players</h2>
</div>

{{ form('players/edit/' ~ id, 'class': 'form-horizontal') }}

<fieldset>
    {{ hidden_field('id', 'value': id) }}
    <div class='control-group'>
        <label class='control-label'>Name</label>

        <div class='controls'>
            {{ text_field('name', 'class': 'input-xlarge') }}
            <p class='help-block'>(required)</p>
        </div>
    </div>
    <div class='control-group'>
        <label class='control-label'>Active</label>

        <div class='controls'>
            {{ select_static('active', ['0': '', '1': 'Yes', '2': 'No'], 'class': 'input-xlarge') }}
            <p class='help-block'>(required)</p>
        </div>
    </div>
    <div class='form-actions'>
        {{ submit_button('Send', 'class': 'btn btn-primary btn-large') }}
    </div>
</fieldset>
{{ end_form() }}
