{{ content() }}
            <div ng-controller='MainCtrl'>

            <div>
                <ul class='nav nav-tabs'>
                    <li ng-class="{true:'active',false:''}[area == 0]">
                        <a ng-click='getFiltered(0)'>
                            <i class='icon-list-alt'></i> Summary</a>
                    </li>
                    <li ng-class="{true:'active',false:''}[area == 1]">
                        <a ng-click='getFiltered(1)'>
                            <i class='icon-list-alt'></i> Summary (Active Players)</a>
                    </li>
                    <li ng-class="{true:'active',false:''}[area == 2]">
                        <a ng-click='getFiltered(2)'>
                            <i class='icon-user'></i> Aaron</a>
                    </li>
                    <li ng-class="{true:'active',false:''}[area == 3]">
                        <a ng-click='getFiltered(3)'>
                            <i class='icon-user'></i> Josh</a>
                    </li>
                    <li ng-class="{true:'active',false:''}[area == 4]">
                        <a ng-click='getFiltered(4)'>
                            <i class='icon-user'></i> John</a>
                    </li>
                    <li class='pull-right'>
                        {{ addButton }}
                    </li>
                </ul>
            </div>

            <div>
                <div class='span6 ng-cloak' ng-cloak>
                    <table class='table table-bordered table-condensed'>
                        <thead>
                        <tr>
                            <th colspan='2'>Game Balls Hall of Fame</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat='gb in data.gameballs'>
                            <td style='width:10px;'>[[gb.total]]</td>
                            <td>
                                <div>[[gb.name]]</div>
                                <div class='progress progress-info'>
                                    <div class='bar' style='width:[[gb.percent]]%;'></div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class='span6 ng-cloak' ng-cloak>
                    <table class='table table-bordered table-condensed'>
                        <thead>
                        <tr>
                            <th colspan='2'>Kick In The Balls Hall of Fame</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat='kitb in data.kicks'>
                            <td style='width:10px;'>[[kitb.total]]</td>
                            <td>
                                <div>[[kitb.name]]</div>
                                <div class='progress progress-danger'>
                                    <div class='bar' style='width:[[kitb.percent]]%;'></div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            </div>
