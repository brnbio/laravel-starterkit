@props(['url'])
<tr>
    <td class="header">
        <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td class="content-cell">
                    <a href="{{ $url }}">
                        {!! $slot !!}
                    </a>
                </td>
            </tr>
        </table>
    </td>
</tr>
