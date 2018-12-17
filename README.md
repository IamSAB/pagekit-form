# pagekit-form

Create your custom html forms in the Pagekit Editor. The extension parses the form and makes it interactive with a vue instance.

## Form options

Use html attributes to configure your form. Following options are available:
Only form which have attributes (*) are mounted!

- subject*: mail subject
- title: mail title (default is subject)
- desc: mail description

- to*: send form to adress(es) (multiple seperated by whitespace)
- cc: carbon copy to adress(es)
- bcc: blind carbon copy adress(es)
- replyTo: replyTo mail

If you want use values from your input simply use the input name prefixed with an $.
*It also works with mulitple injections & multiselect/checkboxes of mails.*

## Form inputs

Only inputs with a name attributes get registered.
Every input get is send to the provided adresses as a table of name & value.
File(s) input generate an attachement and a list of files in the table.

## Form submission

The form can be submitted with an `input[type=submit]` or `button[type=submit]`.
For succes and error messages add the proper attribute to an html element. The visibility manages the extension.
After submission the hole form stays disabled.

## Sample form

``` html
<form subject="Contact form" to="2011pbosi@gmail.com invalid@mail" cc="$email $inform" replyto="$email" class="uk-form">

    <h3>
        Credentials
    </h3>

    <div class="uk-grid uk-grid-width-medium-1-3" data-uk-grid-margin>
        <div>
            <select name="designation">
                <option value="mister" selected>Mister</option>
                <option value="madame">Madame</option>
            </select>
        </div>
        <div>
            <select name="titel" multiple>
                <option value="-" selected>-</option>
                <option value="doctor">Doctor</option>
                <option value="professor">Professor</option>
            </select>
        </div>
        <div>
            <select name="inform" multiple>
                <option value="-">-</option>
                <option value="p1@watch.com" selected>P1</option>
                <option value="p2@watch.com">P2</option>
                <option value="p3@watch.com">P3</option>
                <option value="p4@watch.com" selected>P4</option>
            </select>
        </div>
        <div>
            <input type="text" name="firstname" placeholder="First name" class="uk-width-1-1" v-validate:required>
        </div>
        <div>
            <input type="text" name="name" placeholder="Name" class="uk-width-1-1">
        </div>
        <div>
            <input type="date" name="birthday" placeholder="Birthday" class="uk-width-1-1">
        </div>
        <div>
            <input type="url" name="website" placeholder="Website" class="uk-width-1-1">
        </div>
        <div class="uk-form-file">
            <button class="uk-button" type="button">Select CV</button>
            <input type="file" name="cv" accept="application/pdf">
        </div>
    </div>

    <h3>
        Contact
    </h3>

    <div class="uk-grid" data-uk-grid-margin>
        <div class="uk-width-medium-1-2">
            <input type="email" name="email" placeholder="Email" class="uk-width-1-1">
        </div>
        <div class="uk-width-medium-1-4">
            <input type="tel" name="mobil" placeholder="Mobil" class="uk-width-1-1">
        </div>
        <div class="uk-width-medium-1-4">
            <input type="tel" name="tel" placeholder="Tel" class="uk-width-1-1">
        </div>
    </div>

    <h3>
        Adress
    </h3>

    <div class="uk-grid" data-uk-grid-margin>
        <div class="uk-width-medium-1-2">
            <input type="text" name="street" placeholder="Street" class="uk-width-1-1">
        </div>
        <div class="uk-width-medium-1-4">
            <input type="number" name="postcode" min="100" max="10000" placeholder="Postcode" class="uk-width-1-1">
        </div>
        <div class="uk-width-medium-1-4">
            <input type="text" name="place" placeholder="Place" class="uk-width-1-1">
        </div>
    </div>

    <h3>
        Comment
    </h3>

    <div class="uk-grid" data-uk-grid-margin>
        <div class="uk-width-medium-1-2">
            <textarea name="comment" class="uk-width-1-1" rows="5" placeholder="Important things ..."></textarea>
        </div>
    </div>

    <div recaptcha=""></div>

    <div class="g-recaptcha" data-sitekey="..."></div>

    <button type="submit" class="uk-button uk-button-large uk-margin-large-top">
        Submit
    </button>

    <p success class="uk-text-large uk-margin-large-top uk-text-center">
        Form submitted!
    </p>

    <p error class="uk-text-large uk-margin-large-top uk-text-center">
        There was an error!
    </p>
```
