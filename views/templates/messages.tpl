{if $message}
    <div class="alert {if $isError}alert-danger{else}alert-success{/if}">
        {$message}
    </div>
{/if}
