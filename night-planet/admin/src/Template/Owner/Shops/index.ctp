<div id="wrapper">
    <div id="dash-board" class="container">
        <span id="dummy" style="display: hidden;"></span>
        <div class="row">
            <div class="col s12 m12 l12">
                <!-- 編集中の店舗 START-->
                <?= $this->element('now_edit_shop'); ?>
                <!-- 編集中の店舗 END-->
            </div>
            <div class="red darken-1 card-panel col s12 center-align">
                <p class="white-text recruit-label section-label"><span> 店舗 </span></p>
            </div>
            <p style="font-weight: bolder;">店舗アクセスレポートは現在機能を停止中です。近日中に機能を再開いたしますので、それまでしばらくお待ちください。以下のグラフはダミーデータになります。</p>

            <div class="col s12 m6 l6 year-graph-section section">
                <div class="card-panel year-graph-shop section">
                    <canvas id="shopMonthChart" height="300"></canvas>
                </div>
            </div>
            <div class="col s12 m6 l6 year-graph-section section">
                <div class="card-panel year-graph-shop section">
                    <!--描画箇所 -->
                    <canvas id="shopDayOfTheWeekChart" height="300"></canvas>
                    <!--凡例箇所 -->
                    <ul id="chart_legend"></ul>
                </div>
            </div>
            <div class="col s12 m6 l6 day-graph-section section">
                <div class="card-panel day-graph-shop section">
                    <!--描画箇所 -->
                    <canvas id="shopDayChart" height="500"></canvas>
                    <!--凡例箇所 -->
                </div>
           </div>
           <div class="red darken-1 card-panel col s12 center-align">
                <p class="white-text recruit-label section-label"><span> スタッフ </span></p>
            </div>
           <div class="col s12 m6 l6 day-graph-section section">
                <div class="card-panel day-graph-cast section">
                    <!--描画箇所 -->
                    <canvas id="castWeekChart" height="300"></canvas>
                    <!--凡例箇所 -->
                </div>
           </div>
           <div class="col s12 m6 l6 day-graph-section section">
                <div class="card-panel total-graph-cast section">
                    <!--描画箇所 -->
                    <canvas id="castTotalChartCast" height="300"></canvas>
                    <!--凡例箇所 -->
                </div>
           </div>
        </div>
    </div>
</div>
<script>


    /**
     * 曜日別アクセス状況作成
     */
    var shopDayOfTheWeekChart = function() {

        var ctx = document.getElementById("shopDayOfTheWeekChart");
        var shopDayOfTheWeekChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
            labels: ["月曜", "火曜", "水曜", "木曜", "金曜", "土曜", "日曜"],
            datasets: [{
                backgroundColor: [
                    "#ff8cd1",
                    "#00ec5f",
                    "#ffeb00",
                    "#1995ff",
                    "#ff8303",
                    "#ff0000",
                    "#d800ff"
                ],
                data: [38, 31, 21, 10, 50, 20, 5]
            }]
            },
            options: {
                title: {
                    display: true,
                    text: ['曜日別 割合',
                        ]
                }
            }
        });
    }

     /**
     * 月間別アクセス状況作成
     */
    var shopMonthChart = function() {

        var ctx = document.getElementById('shopMonthChart').getContext('2d');
        var chart = new Chart(ctx, {
            // The type of chart we want to create
            type: 'line',

            // The data for our dataset
            data: {
                labels: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
                datasets: [{
                    label: '月間アクセス状況',
                    backgroundColor: 'rgb(255, 25, 102)',
                    borderColor: 'rgb(255, 99, 132)',
                    data: [0, 10, 5, 2, 20, 30, 45, 0, 10, 5, 2, 20, 30, 45, 30, 45]
                }]
            },

            // Configuration options go here
            options: {
                title: {
                    display: true,
                    text: '２０１９年'
                }
            }
        });
    }

    /**
     * 日別アクセス状況作成
     */
    var shopDayChart = function() {

        var ctx = document.getElementById('shopDayChart').getContext('2d');
        var chart = new Chart(ctx, {
            // The type of chart we want to create
            type: 'horizontalBar',

            // The data for our dataset
            data: {
                labels: ['1日','2日','3日','4日','5日'
                    ,'6日','7日','8日','9日','10日'
                    ,'11日','12日','13日','14日','15日'
                    ,'16日','17日','18日','19日','20日'
                    ,'21日','22日','23日','24日','25日'
                    ,'26日','27日','28日','29日','30日','31日'],
                datasets: [{
                    label: '日別アクセス状況',
                    backgroundColor: 'rgb(255, 25, 102)',
                    borderColor: 'rgb(255, 99, 132)',
                    data: [0, 10, 5, 2, 20, 30, 45, 0, 10, 5, 2, 20, 30, 45, 30,0, 10, 5, 2, 20, 30, 45, 0, 10, 5, 2, 20, 30, 45, 30]
                }]
            },
            options: {
                title: {
                    display: true,
                    text: '９月３０～１０月６日'
                }
                // responsive: true,
                // maintainAspectRatio: false,
                // 各種設定の記述
            }

        });
    }
    /**
     * 週別アクセス状況作成
     */
    var castWeekChart = function() {

        var ctx = document.getElementById('castWeekChart').getContext('2d');
        var chart = new Chart(ctx, {
            // The type of chart we want to create
            type: 'horizontalBar',

            // The data for our dataset
            data: {
                labels: ['ユーザー１','ユーザー２','ユーザー３','ユーザー４','ユーザー５'],
                datasets: [{
                    label: '週別アクセス状況',
                    backgroundColor: 'rgb(255, 25, 102)',
                    borderColor: 'rgb(255, 99, 132)',
                    data: [0, 10, 5, 2, 20]
                }]
            },
            options: {
                title: {
                    display: true,
                    text: ['９月３０～１０月６日',
                        ]
                }
                // responsive: true,
                // maintainAspectRatio: false,
                // 各種設定の記述
            }

        });
    }

    /**
     * 週別アクセス状況作成
     */
    var castTotalChartCast = function() {

        var ctx = document.getElementById('castTotalChartCast').getContext('2d');
        var chart = new Chart(ctx, {
            // The type of chart we want to create
            type: 'horizontalBar',

            // The data for our dataset
            data: {
                labels: ['ユーザー１','ユーザー２','ユーザー３','ユーザー４','ユーザー５'],
                datasets: [{
                    label: 'トータルアクセス状況',
                    backgroundColor: 'rgb(255, 25, 102)',
                    borderColor: 'rgb(255, 99, 132)',
                    data: [0, 10, 5, 2, 20]
                }]
            },
            options: {
                // responsive: true,
                // maintainAspectRatio: false,
                // 各種設定の記述
            }

        });
    }

    shopMonthChart();
    shopDayOfTheWeekChart();
    shopDayChart();
    castWeekChart();
    castTotalChartCast();
    $(document).ready(function() {
        $(".card-panel").css("padding", "5px");
    });

</script>
