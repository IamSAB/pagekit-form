#  SAB Form

Create your custom html forms inside the native Pagekit Editor allowing submission via mail. **Simply code your form with your own html and css.**

Everything within a classic `form` tag having the special `send` tag with a `to` attribute will be made interactive with a Vue component anchored to your form.

**Use this plugin if you have a basic knowledge of html and css coding.**
Otherwise you will struggle with this extension. An advantage is also familiarity with Vue JS as you can use its syntax inside the form tags.

# Guide

``` html
<form>
    <!-- CONTENT -->
    <send to="sab@me.com">
    </send>
    <!-- CONTENT -->
<form>
```

Every form including the send tag with the `to=""` attribute will be considered as SAB Form. **The hole form configuration is done via attributes on the send tag.**

Only inputs with `name` attributes are values which will can be sent as mail.

## Configuration

- subject: mail subject (defaults to `{Sitename} - #{index} form at {Pagename}`)
- priority: 1 (highest) - 5 (lowest)
- title: title (defaults to `subject`)
- desc: description
- tmpl: path to a php template for rendering the email body
- to*: send form to adress(es)
- cc*: carbon copy to adress(es)
- bcc*: blind carbon copy adress(es)
- replyTo*: replyTo adress(es)

The attributes are also [Vue JS Props](https://v1.vuejs.org/guide/components.html#Literal-vs-Dynamic). Means static values are bound with `to="sab@me.com"`, dynamic ones with `:to="values.emails"`. All form inputs are available in `values`.

Adresses (*) can be of following formats
- `to="sab@mail"` -> one adress without name
- `:to="['sab1@mail','sab2@mail', ...]"` -> multiple adresses without name
- `:to="[['sab@mail', 'SAB']]"` -> one adress with name
- `:to="[[sab1@mail, SAB1], [sab2@mail, SAB2], ... ]"` -> multiple adresses with name

## Mail

Every input is send via mail to the provided adresses as a table of name & value. File(s) input generate an attachement and a list of files in the table.

## Handling

``` html
<send class="[class]">
    <span slot="[name]">
        Your custom html goes here!
    </span>
</send>
```

The send tag is rendered as button[type=submit]. To style it use the class attribute.

If you want change the inner html of the button dependend on the status of the form, use whatever html tag you want a with slot attribute having value `sending, success or error`. Elements without slot are rendered as default.

## Validation

Use listed attributes for validation (* have no value):

- required*, numeric*, integer*, float*, alpha*, alphanum*, email*, url*, minlength, maxlength, length, min, max, pattern

To provide error messages, you can use the variable `form` in which different states (dirty, invalid, required, touched, valid) for each input are stored as Booleans which say, if the constraint if fullfilled.

Show validation messages: `<p v-if="form.{input_name}.required">Provide a correct email!</p>`

## Google reCAPTCHA

Used automaticly if you configured it correctly in Pagekit.

## Customize

If your common with Vue JS, you can use all its directives & syntay inside the form tag.
*Useful if you want to show content dependend on form status and input values using `v-if`or `v-show` directives.*

Relevant variables:
- status (of the form)
   - 0: default
   - 1: sending
   - 2: success/sent
   - 3: error
- values (your form input)

# Test form

Used to test this extension. Simply paste it to your editor, save the page and it should work out of the box.

For Uikit v3 just adapt classes!

```html
<form class="uk-form uk-form-horizontal">

    <div class="uk-form-row">
        <label class="uk-form-label">To</label>
        <div class="uk-form-controls">
            <select name="to" multiple required>
                <option value="a@s.ch" selected>A</option>
                <option value="b@s.ch">B</option>
				<option value="c@s.com">C</option>
                <option value="d@s.com" selected>D</option>
            </select>
        </div>
    </div>
    <div class="uk-form-row">
        <label class="uk-form-label">You</label>
        <div class="uk-form-controls">
            <input placeholder="Name" type="text" name="name" required> <br>
            <input type="radio" name="gender" value="Male" required>Male
            <input type="radio" name="gender" value="Female">Female <br>
            <input placeholder="Age" type="number" name="age" min="0" max="125">
        </div>
    </div>
    <div class="uk-form-row">
        <label class="uk-form-label">Email</label>
        <div class="uk-form-controls">
            <input type="email" name="email" required>
        </div>
    </div>
    <div class="uk-form-row">
        <label class="uk-form-label">Message</label>
        <div class="uk-form-controls">
            <textarea class="uk-form-width-large" name="message" row="6" maxlength="1000"></textarea>
        </div>
    </div>

    <div class="uk-form-row">
        <label class="uk-form-label">Interests</label>
        <div class="uk-form-controls">
            <input type="checkbox" name="interests" value="Sports"> Sports <br>
            <input type="checkbox" name="interests" value="IT"> IT <br>
            <input type="checkbox" name="interests" value="Photography"> Photography <br>
            <input type="checkbox" name="interests" value="Reading"> Reading <br>
        </div>
    </div>

    <div class="uk-form-row">
        <label class="uk-form-label">Multiple PDF's</label>
        <div class="uk-form-controls">
            <input type="file" name="pdf" accept="application/pdf" multiple>
        </div>
    </div>

    <div class="uk-form-row">
        <div class="uk-form-controls">
            <send class="uk-button"
            	:to="values.to"
                :cc="[[values.email, values.name]]"
                :reply-to="values.email"
                priority="1"
                title="SAB Form - Test"
                desc="Great! SAB Form works.">
            	<i class="uk-icon-spinner uk-icon-spin" slot="sending"></i>
            </send>
        </div>
    </div>

</form>
```