<div id="$Name" class="SimpleCaptchaField field">
    <% if $Title %><label class="left" for="$ID">$Title</label><% end_if %>
    <div class="middleColumn">
        <label class="input input-captcha">
            <img src="{$SkyImageLink}" width="100" height="35" alt="Captcha image"/>
            <input $AttributesHTML />
        </label>

    </div>
    <% if $RightTitle %><label class="right right-title" for="$ID">$RightTitle</label><% end_if %>
    <% if $Message %><span class="message $MessageType">$Message</span><% end_if %>
    <% if $Description %><span class="description">$Description</span><% end_if %>
</div>
