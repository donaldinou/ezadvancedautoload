{include uri='design:parts/setup/warning_messages.tpl'}

<form name="extensionform" method="post" action="{'/ezadvancedautoload/extensions'|ezurl('no')}">
    <div class="context-block">

        {* DESIGN: Header START *}
        <div class="box-header">
            <h1 class="context-title">
                {'Available extensions (%extension_count)'|i18n( 'design/admin/setup/extensions',, hash( '%extension_count', $available_extension_array|count() ) )}
            </h1>
            {* DESIGN: Mainline *}
            <div class="header-mainline"></div>
        </div>
        {* DESIGN: Header END *}

        {* DESIGN: Content START *}
        <div class="box-content">
            {if and( is_set($available_extension_array), is_array($available_extension_array) )}
                <table class="list" cellspacing="0">
                    <tr>
                        <th class="tight">
                            <img src="{'toggle-button-16x16.gif'|ezimage('no')}"
                                 alt="{'Invert selection.'|i18n( 'design/admin/setup/extensions' )}"
                                 title="{'Toggle all.'|i18n( 'design/admin/content/translations' )}"
                                 onclick="ezjs_toggleCheckboxes( document.extensionform, 'ActiveExtensionList[]' ); return false;"/>
                        </th>
                        <th>{'Name'|i18n( 'design/admin/setup/extensions' )}</th>
                    </tr>
                    {foreach $available_extension_array as $extension sequence array( 'bglight', 'bgdark' ) as $style}
                        <tr class="{$style}">
                            <td>
                               <input type="checkbox"
                                      name="ActiveExtensionList[]"
                                      value="{$extension}"
                                      {if and( is_set($selected_extension_array), $selected_extension_array|contains($extension) )}checked="checked"{/if}
                                      title="{'Activate or deactivate extension. Use the "Update" button to apply the changes.'|i18n( 'design/admin/setup/extensions' )|wash}" />
                            </td>
                            <td>{$extension}</td>
                        </tr>
                    {/foreach}
                </table>
            {else}
                <div class="block">
                    <p>{'There are no available extensions.'|i18n( 'design/admin/setup/extensions' )}</p>
                </div>
            {/if}
        </div>
        {* DESIGN: Content END *}

        {* DESIGN: Control bar START *}
        <div class='block'>
            <div class="controlbar">
                <div class="block">
                    {if and( is_set($available_extension_array), is_array($available_extension_array) )}
                        <input class="button"
                               type="submit" name="ActivateExtensionsButton"
                               value="{'Update'|i18n( 'design/admin/setup/extensions' )}"
                               title="{'Click this button to store changes if you have modified the status of the checkboxes above.'|i18n( 'design/admin/setup/extensions' )}" />
                    {else}
                        <input class="button-disabled"
                               type="submit" name="ActivateExtensionsButton"
                               value="{'Apply changes'|i18n( 'design/admin/setup/extensions' )}" disabled="disabled" />
                    {/if}
                    <input class="button"
                           type="submit"
                           name="GenerateAutoloadArraysButton"
                           value="{'Regenerate autoload arrays for extensions'|i18n( 'design/admin/setup/extensions' )}"
                           title="{'Click this button to regenerate the autoload arrays used by the system for extensions.'|i18n( 'design/admin/setup/extensions' )}" />

                    {* Regenerate Autoloads adding -Override parameter *}
                    <input class="button"
                           type="submit"
                           name="GenerateAutoloadOverrideArraysButton"
                           value="{'Regenerate autoload arrays for extensions -override'|i18n( 'design/ezadvancedautoload/setup/extensions' )}"
                           title="{'Click this button to regenerate the autoload arrays used by the system for extensions and overrides.'|i18n( 'design/ezadvancedautoload/setup/extensions' )}" />
                </div>
            </div>
        </div>
        {* DESIGN: Control bar END *}

    </div>
</form>

{def $authorized_lib = array( 'jquery' )}
{def $preferred_lib = ezini('eZJSCore', 'PreferredLibrary', 'ezjscore.ini')}{if $authorized_lib|contains( $preferred_lib )|not()}{set $preferred_lib = 'jquery'}{/if}
{ezscript_require( array( concat( 'ezjsc::', $preferred_lib ), concat( 'ezjsc::', $preferred_lib, 'io' ), concat( 'updatebuttonstyle_', $preferred_lib, '.js' ) ) )}
{undef $authorized_lib $preferred_lib}

{if is_set($available_extension_array)}{undef $available_extension_array}{/if}
{if is_set($selected_extension_array)}{undef $selected_extension_array}{/if}