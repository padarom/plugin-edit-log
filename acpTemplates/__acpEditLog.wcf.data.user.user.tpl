<dl>
    <dt></dt>
    <dd><strong>The following changes have been made:</strong></dd>
</dl>

{if $type == 'edit'}
    {foreach from=$changes item='change' key='name'}
        {if $name == 'options'}
            {foreach from=$change item='option' key='optionName'}
                <dl>
                    <dt>{lang}wcf.user.option.{$optionName}{/lang}</dt>
                    <dd>
                        <p><b>B:</b> {$option['before']}</p>
                        <p><b>A:</b> {$option['after']}</p>
                    </dd>
                </dl>
            {/foreach}
        {elseif $name == 'usergroups'}
            {if $change[added]|isset}
                <dl>
                    <dt>{lang}wcf.acp.editlog.addedToUsergroup{/lang}</dt>
                    <dd>
                        <ul>
                            {foreach from=$change[added] item='added'}
                                <li>{$added->groupName}</li>
                            {/foreach}
                        </ul>
                    </dd>
                </dl>
            {/if}
            {if $change[removed]|isset}
                <dl>
                    <dt>{lang}wcf.acp.editlog.removedFromUsergroup{/lang}</dt>
                    <dd>
                        <ul>
                            {foreach from=$change[removed] item='removed'}
                                <li>{$removed->groupName}</li>
                            {/foreach}
                        </ul>
                    </dd>
                </dl>
            {/if}
        {else}
            <dl>
                <dt>{lang}wcf.user.{$name}{/lang}</dt>
                <dd>
                    <p><b>B:</b> {$change['before']}</p>
                    <p><b>A:</b> {$change['after']}</p>
                </dd>
            </dl>
        {/if}
    {/foreach}
{/if}