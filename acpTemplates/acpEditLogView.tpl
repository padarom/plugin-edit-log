{include file='header' pageTitle='wcf.acp.acpEditLog'}

<script data-relocate="true">
    //<![CDATA[
    $(function() {
        new WCF.Search.User('#username');
    });
    //]]>
</script>

<header class="boxHeadline">
    <h1>{lang}wcf.acp.acpEditLog{/lang}</h1>
</header>

{include file='formError'}

<form method="post" action="{link controller='ACPEditLogView'}{/link}">
    <div class="container containerPadding marginTop">
        <fieldset><legend>{lang}wcf.acp.exceptionLog.search{/lang}</legend>
            <dl>
                <dt><label for="username">{lang}wcf.user.username{/lang}</label></dt>
                <dd>
                    <input type="text" id="username" name="username" value="{$username}" class="medium" />
                    <small>{lang}wcf.acp.editlog.username.description{/lang}</small>
                </dd>
            </dl>

            <dl>
                <dt><label for="logDateStart">{lang}wcf.acp.editlog.date{/lang}</label></dt>
                <dd>
                    <input type="date" id="logDateStart" name="logDateStart" value="{$logDateStart}" placeholder="{lang}wcf.date.period.start{/lang}" />
                    <input type="date" id="logDateEnd" name="logDateEnd" value="{$logDateEnd}" placeholder="{lang}wcf.date.period.end{/lang}" />
                </dd>
            </dl>

            <dl>
                <dt><label for="objectType">{lang}wcf.acp.editlog.type{/lang}</label></dt>
                <dd>
                    <select id="objectType" name="objectType">
                        {htmlOptions options=$objectTypes selected=$objectType}
                    </select>
                </dd>
            </dl>
        </fieldset>
    </div>

    <div class="formSubmit">
        <input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
        {@SID_INPUT_TAG}
    </div>
</form>

<div class="contentNavigation">
    {pages print=true controller="ACPEditLogView" link="pageNo=%d"}

    {hascontent}
        <nav>
            <ul>
                {content}
                {event name='contentNavigationButtonsTop'}
                {/content}
            </ul>
        </nav>
    {/hascontent}
</div>

{if $items}
    {foreach from=$objects->getObjects() item='object' key='objectKey'}
        <div id="{$objectKey}" class="container containerPadding marginTop">
            <fieldset>
                <legend>
                    {hascontent}
                        {content}{lang __optional=true object=$object}wcf.acp.editlog.title.{$object->getClassDescriptor()}.{$object->type}{/lang}{/content}
                    {hascontentelse}
                        {lang object=$object type=$object->getObjectDescriptor()}wcf.acp.editlog.title.{$object->type}{/lang}
                    {/hascontent}
                </legend>

                <dl>
                    <dt>{lang}wcf.acp.editlog.time{/lang}</dt>
                    <dd>{$object->time|strtotime|plainTime}</dd>
                </dl>

                <dl>
                    <dt>{lang}wcf.acp.editlog.edited{/lang}</dt>
                    <dd>{$object->getObjectDescriptor()} (ID: {$object->objectID})</dd>
                </dl>

                {include file='__acpEditLog.'|concat:$object->getClassDescriptor() editedObject=$object->getEditedObject() type=$object->type changes=$object->changes}
            </fieldset>
        </div>
    {/foreach}
{else}
    <p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}

{include file='footer'}
