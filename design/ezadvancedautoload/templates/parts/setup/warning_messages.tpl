{if and( is_set( $warning_messages), $warning_messages|count|ge(1) )}
    <div class="message-warning">
        <h2>
            <span class="time">[{currentdate()|l10n( 'shortdatetime' )}]</span>
            {'Problems detected during autoload generation:'|i18n( 'design/admin/setup/extensions' )}
        </h2>
        <ul>
            {foreach $warning_messages as $warning}
                <li><p>{$warning|break()}</p></li>
            {/foreach}
        </ul>
    </div>
{/if}