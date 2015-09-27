{{ content() }}

<div>
    <ul class='nav nav-tabs'>
        <li class='pull-right'>
            {{ addButton }}
        </li>
    </ul>
</div>

<div ng-controller='MainCtrl'>
<table class='table table-bordered table-striped ng-cloak' ng-cloak>
    <thead>
    <tr>
        <th><a href='' ng-click="predicate='number'; reverse=!reverse">#</a></th>
        <th><a href='' ng-click="predicate='air_date'; reverse=!reverse">Date</a></th>
        <th><a href='' ng-click="predicate='outcome'; reverse=!reverse">W/L</a></th>
        <th><a href='' ng-click="predicate='summary'; reverse=!reverse">Summary</a></th>
    </tr>
    </thead>
    <tbody>
        <tr ng-repeat="episode in data.results | orderBy:predicate:reverse">
            <td>[[episode.number]]</td>
            <td width='7%'>[[episode.air_date]]</td>
            <td>[[episode.outcome]]</td>
            <td>[[episode.summary]]</td>
            {% if (addButton) %}
            <td width='1%'><a href='/episodes/edit/[[episode.id]]'><i class='icon-pencil'></i></a></td>
            <td width='1%'><a href='/episodes/delete/[[episode.id]]'><i class='icon-remove'></i></a></td>
            {% endif %}
        </tr>
    </tbody>
</table>
</div>