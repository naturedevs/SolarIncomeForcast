
<div id="big_wrapper">
    <div class="Big_container">
        <div class="big_content_area">

            <div class="r_sidebar" id="products-r-sidebar">
                <div id="inner-box">

                    {if $success}

                        <div class="alert alert-success">
                            <p><strong>{$ADDONLANG.success}</strong> {$ADDONLANG.successinfo}</p>
                        </div>

                    {/if}
                    <div style="float:left;">
                        <form method="post" action="clientarea.php?action=productdetails&id={$serviceid}">
                            <input type="submit" value="{$ADDONLANG.back}" class="btn btn-warning btn-large" />
                        </form>
                    </div>

                    <div style="float:left; margin:0 0 20px 10px;">
                        <form method="post" action="index.php?m=uptimemonitor">
                            <input type="hidden" name="id" value="{$serviceid}" />
                            <input type="hidden" name="action" value="addnew" />
                            <input type="submit" value="{$ADDONLANG.addnew}" class="btn btn-success btn-large" />
                        </form>
                    </div>
                    <div style="float:left; margin:0 0 20px 10px;">
                        <form method="post" action="index.php?m=uptimemonitor">
                            <input type="hidden" name="id" value="{$serviceid}" />
                            <input type="hidden" name="action" value="addcontact" />
                            <input type="submit" value="{$ADDONLANG.additionalcontact}" class="btn btn-info btn-large" />
                        </form>
                    </div>
                    <table class="table table-striped table-framed table-centered">
                        <thead>
                            <tr>
                                <th>{$ADDONLANG.ipaddress}</th>
                                <th>{$ADDONLANG.port}</th>
                                <th>{$ADDONLANG.monitortype}</th>
                                <th>{$ADDONLANG.status}</th>
                                <th>{$ADDONLANG.uptime}</th>
                                <th>{$ADDONLANG.downtime}</th>
                                <th>{$ADDONLANG.checktime}</th>
                                <!-- <th>{$ADDONLANG.rechecktime}</th> -->
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$ip item=ip1}
                                <tr>
                                    <td>{$ip1.ip}
                                        {if $ip1.suspend == 1}
                                            ({$ADDONLANG.suspended})
                                        {/if}
                                    </td>
                                    <td style="color:black;width: 80px;"  >{$ip1.port}</td>
                                    <td>{$ip1.ctype}</td>
                                    <td>{if $ip1.status}<span style="color:green;"   class="label active">{if $ip1.ctype eq "blacklist"}Clean{else}{$ADDONLANG.active}{/if}</span>{else}<span  style="color:red;"  class="label label-important">{if $ip1.ctype eq "blacklist"}Blacklisted{else}{$ADDONLANG.down}{/if}</span>{/if}</td>
                                    <td>{$ip1.uptime}</td>
                                    <td>{$ip1.downtime}</td>
                                    <td>{$ip1.response_time}</td>
                                    <!-- <td>{$ip1.retest_time}</td> -->
                                    <td class="textcenter">
                                        <a href="index.php?m=uptimemonitor&action=uptimehistory&id={$ip1.id}&sid={$serviceid}" class="btn btn-info" style="width: 100%; margin-bottom: 3px;">{$ADDONLANG.uptimemonthly}</a> {if $ip1.suspend == 1}
                                            <a href="index.php?m=uptimemonitor&action=unsuspend&id={$ip1.id}&sid={$serviceid}" class="btn btn-primary" style="width: 100%; margin-bottom: 3px;">{$ADDONLANG.unsuspend}</a>
                                        {else}
                                            <a href="index.php?m=uptimemonitor&action=suspend&id={$ip1.id}&sid={$serviceid}" class="btn btn-warning" style="width: 100%; margin-bottom: 3px;">{$ADDONLANG.suspend}</a>
                                        {/if}
                                        </td>
                                        <td> <form class="form-horizontal" method="post" action="index.php?m=uptimemonitor">
                                            <input type="hidden" name="id" value="{$serviceid}" />
                                            <input type="hidden" name="eid" value="{$ip1.id}" />
                                            <input type="hidden" name="action" value="addnew" />
                                            <input type="submit" value="{$ADDONLANG.edit}" class="btn btn-info" style="width: 100%; margin-bottom: 3px;" />
                                        </form> <a href="index.php?m=uptimemonitor&action=deletemonitor&id={$ip1.id}&sid={$serviceid}" class="btn btn-danger" style="width: 100%; margin-bottom: 3px;">{$ADDONLANG.delete}</a></td>
                                </tr>
                            {foreachelse}
                                <tr>
                                    <td colspan="6" class="textcenter">{$ADDONLANG.norecordsfound}</td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
