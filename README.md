# EXTENSION pagekit-form

Create your custom html forms in the Pagekit Editor. Simply create a wrap your form inside a form tag with the correct attributes and the extension will parse and mount it to make it interactive. You can use mulitple forms on a single page.

# Basics

## Options

Use html attributes to configure your form. Add them to your form tag.

- subject: mail subject (defaults to [{Site} - form #{index} at {url}])
- title: mail title (defaults to [subject])
- desc: mail description

- to*: send form to adress(es) (multiple seperated by whitespace)
- cc: carbon copy to adress(es)
- bcc: blind carbon copy adress(es)
- replyTo: replyTo adress(es)
- priority: 0 (highest) - 5 (lowest)

**(*) is mandatory for the form to be mounted**

If you want use values from your form inputs, just use the input name prefixed with an `$`.
*It also works with mulitple injections & multiselect/checkboxes of mails.*

## Inputs

Only inputs with `name` attributes get registered.

## Mail

Every input is send via mail to the provided adresses as a table of name & value. File(s) input generate an attachement and a list of files in the table.

## Handling

The form is submitted with `input[type=submit]`. For succes and error messages, add the proper attribute to an html element. The visibility manages the extension.

- success `<div success>Form successfully submitted</div>`
- error `<div error>There was an error. Please reload the page and try again!</div>`

After submission the hole form stays disabled.

## Google reCAPTCHA

Enter your sitekey and secret under `Site > Settings > Recaptcha`. The forms now use the invisible reCAPTCHA for validation. Without a sitekey, it's disabled.

# Advanced

## Validation

### HTML 5

- types: email, url, number, range, color, date, datetime, datetime-local, month, search, tel, time, week
- attributes: required, pattern

*This validation type is dependend on the client browser!*

*When using e.g. the required attribute in the backend, the save button does not work. Use instead Ctrl+s, then the form is saved.*

### Vue JS

More advanced validation is possible with the vue validation plugin of pagekit.

- v-validate: required*, numeric*, integer*, float*, alpha*, alphanum*, email*, url*, minlength, maxlength, length, min, max, pattern
- e.g. no value*: `v-validate:required`, with value: `v-validate:minlength="256"`

To provide error messages, you can use the variable `form` in which different states (dirty, invalid, required, touched, valid) for each input are stored.

e.g. `<p v-if="form.email.invalid">Provide a correct email!</p>`

## Customize

Actually you can use the hole Vue JS instance inside the form tag. The greater your HTML & CSS skills, the better your forms get. Have fun!

### Available variables

- values: all registered inputs
- form: validation object
- status: 0 = nothing, 1 = sending, 2 = submitted, 3 = error,
- i: index of form (for multiple forms per site)
- mail: contains subject, title, desc, priority (if provided)
- adresses: contains to, cc, bcc, replyto (if provided)

# Examples

## Contact

``` html
<form
	subject="Contact"
    to="$contact"
    cc="$email"
    replyo="$email"
	class="uk-form uk-form-horizontal">

    <div class="uk-form-row">
        <label class="uk-form-label">Departement</label>
        <div class="uk-form-controls">
            <select name="contact">
                <option value="mavt@d.ch" selected>D-MAVT</option>
                <option value="itet@d.ch">D-ITET</option>
				<option value="math@d.com">D-MATH</option>
            </select>
        </div>
    </div>
    <div class="uk-form-row">
        <label class="uk-form-label">Name</label>
        <div class="uk-form-controls">
            <input type="text" name="name">
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
            <textarea class="uk-form-width-large" name="message" row="6"></textarea>
        </div>
    </div>

    <div class="uk-form-row">
        <div class="uk-form-controls">
            <input class="uk-button" type="submit">
        </div>
    </div>

</form>
```

## Appointment

``` html
<form
    subject="Appointment"
	to="hr@c.com"
    replyto="$email"
    class="uk-form uk-form-horizontal">

    <div class="uk-form-row">
        <label class="uk-form-label">Concern</label>
        <div class="uk-form-controls">
            <textarea class="uk-form-width-large" name="message" row="6" v-validate:required></textarea>
        </div>
    </div>

    <div class="uk-form-row">
        <label class="uk-form-label">Date & time</label>
        <div class="uk-form-controls">
            <input type="datetime-local" name="datetime" v-validate:required>
        </div>
    </div>

    <div class="uk-form-row">
        <label class="uk-form-label">Name</label>
        <div class="uk-form-controls">
            <input type="text" name="name">
        </div>
    </div>

    <div class="uk-form-row">
        <label class="uk-form-label">Email</label>
        <div class="uk-form-controls">
            <input type="email" name="email" v-validate:required>
        </div>
    </div>

    <div class="uk-form-row">
        <div class="uk-form-controls">
            <input class="uk-button" type="submit">
        </div>
    </div>

</form>
```

## Registration

``` html
<form
    subject="Springtime Festival"
	to="festival@sp.ch"
    cc="$email"
    replyto="$email"
    class="uk-form uk-form-stacked">

    <div class="uk-form-row">
        <label class="uk-form-label">Role</label>
        <div class="uk-form-controls">
            <select name="contact">
                <option value="staff">Staff</option>
                <option value="musician">Musician</option>
				<option value="visitor" selected>Visitor</option>
            </select>
        </div>
    </div>

    <div class="uk-form-row">
        <label class="uk-form-label">Attendance</label>
        <div class="uk-form-controls">
            <select name="attendance">
                <option value="monday" selected>Monday</option>
                <option value="tuesday" selected>Tuesday</option>
				<option value="wednesday" selected>Wednesday</option>
            </select>
        </div>
    </div>

    <div class="uk-form-row">
        <label class="uk-form-label">Name</label>
        <div class="uk-form-controls">
            <input type="text" name="name" v-validate:required>
        </div>
    </div>

    <div class="uk-form-row">
        <label class="uk-form-label">Age</label>
        <div class="uk-form-controls">
            <input type="number" name="number" v-validate:min="10" number>
        </div>
    </div>

    <div class="uk-form-row">
        <label class="uk-form-label">Gender</label>
        <div class="uk-form-controls">
            <label><input type="radio" name="gender" value="male"> Male</label>
            <label><input type="radio" name="gender" value="female"> Female</label>
        </div>
    </div>

    <div class="uk-form-row">
        <label class="uk-form-label">Email</label>
        <div class="uk-form-controls">
            <input type="email" name="email" v-validate:required>
        </div>
    </div>

    <div class="uk-form-row">
        <label class="uk-form-label">Comment</label>
        <div class="uk-form-controls">
            <textarea class="uk-form-width-large" name="comment" row="6"></textarea>
        </div>
    </div>

    <div class="uk-form-row">
        <div class="uk-form-controls">
            <input class="uk-button" type="submit">
        </div>
    </div>

</form>
```

## Credentials

``` html
<form
    subject="Contact form"
    to="2011pbosi@gmail.com invalid@mail"
    cc="$email $inform"
    replyto="$email"
    class="uk-form">

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

    <div class="uk-grid">
        <div class="uk-width-medium-1-2">
            <input type="submit" value="Submit">
        </div>
    </div>

    <p success class="uk-margin-top uk-text-large uk-margin-large-top uk-text-center">
        Form submitted!
    </p>

    <p error class="uk-text-large uk-margin-large-top uk-text-center">
        There was an error!
    </p>
</form>
```
