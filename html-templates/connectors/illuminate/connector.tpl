{extends designs/site.tpl}

{block title}Illuminate Connector &mdash; {$dwoo.parent}{/block}

{block content}
    <h1>Illuminate Connector</h1>

    {if $exportKey}
        <ul>
            <li><a href="{$connectorBaseUrl}/students.csv?export_key={$exportKey|escape}" class="button">Secure Students Spreadsheet Link</a></li>
        </ul>
    {else}
        <p class="error">Edit <code>php-config/Slate/Connectors/Illuminate/Connector.config.php</code> and set <code>$exportKey</code> to a secure token to enable student exports</p>
    {/if}
{/block}