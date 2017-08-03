<?php
    include('../app/classLoad.php');    
    session_start();
    if(isset($_SESSION['userMerlaTrav']) ){   
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <?php include('../include/head.php') ?>
</head>
<body class="fixed-top">
    <div class="header navbar navbar-inverse navbar-fixed-top">
        <?php 
            include('../include/top-menu.php'); 
            //admin
            $adminTasksTotalNumber = $taskManager->getTaskNumberByUser('admin')+$taskManager->getTaskDoneNumberByUser('admin');
            $adminTasksNotDoneNumber = $taskManager->getTaskNumberByUser('admin');
            $adminTasksDoneNumber = $taskManager->getTaskDoneNumberByUser('admin');
            //laila
            $lailaTasksTotalNumber = $taskManager->getTaskNumberByUser('laila')+$taskManager->getTaskDoneNumberByUser('laila');
            $lailaTasksNotDoneNumber = $taskManager->getTaskNumberByUser('laila');
            $lailaTasksDoneNumber = $taskManager->getTaskDoneNumberByUser('laila');
            //ikram
            $ikramTasksTotalNumber = $taskManager->getTaskNumberByUser('ikram')+$taskManager->getTaskDoneNumberByUser('ikram');
            $ikramTasksNotDoneNumber = $taskManager->getTaskNumberByUser('ikram');
            $ikramTasksDoneNumber = $taskManager->getTaskDoneNumberByUser('ikram');
            //tijani
            $tijaniTasksTotalNumber = $taskManager->getTaskNumberByUser('tijani')+$taskManager->getTaskDoneNumberByUser('tijani');
            $tijaniTasksNotDoneNumber = $taskManager->getTaskNumberByUser('tijani');
            $tijaniTasksDoneNumber = $taskManager->getTaskDoneNumberByUser('tijani');
            //abdelghani
            $abdelghaniTasksTotalNumber = $taskManager->getTaskNumberByUser('abdelghani')+$taskManager->getTaskDoneNumberByUser('abdelghani');
            $abdelghaniTasksNotDoneNumber = $taskManager->getTaskNumberByUser('abdelghani');
            $abdelghaniTasksDoneNumber = $taskManager->getTaskDoneNumberByUser('abdelghani');
            //hamid
            $hamidTasksTotalNumber = $taskManager->getTaskNumberByUser('hamid')+$taskManager->getTaskDoneNumberByUser('hamid');
            $hamidTasksNotDoneNumber = $taskManager->getTaskNumberByUser('hamid');
            $hamidTasksDoneNumber = $taskManager->getTaskDoneNumberByUser('hamid');
        ?>   
    </div>    
    <div class="page-container row-fluid sidebar-closed">
        <?php include('../include/sidebar.php') ?>
        <div class="page-content">
            <div class="container-fluid">
                <div class="row-fluid">
                    <div class="span12">
                        <ul class="breadcrumb">
                            <li><i class="icon-dashboard"></i> <a href="dashboard.php">Accueil</a><i class="icon-angle-right"></i></li>
                            <li><i class="icon-bar-chart"></i> <a><strong>Statistiques du Personnel</strong></a></li>
                        </ul>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <h4><i class="icon-bar-chart"></i> Statistiques du personnel</h4>
                        <hr class="line">
                        <div id="container" style="width:100%; height:400px;"></div>
                    </div>
                </div>
            </div>  
        </div>       
    </div>
    <?php include('../include/footer.php') ?>
    <?php include('../include/scripts.php') ?>                
    <script>jQuery(document).ready(function() { App.setPage("sliders"); App.init(); });</script>
    <!------------------------- BEGIN HIGHCHARTS  --------------------------->
    <script src="http://code.highcharts.com/highcharts.js"></script>
    <!--script src="http://code.highcharts.com/themes/dark-unica.js"></script-->
    <script src="http://code.highcharts.com/modules/data.js"></script>
    <script src="http://code.highcharts.com/modules/exporting.js"></script>
    <script> 
        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Statistiques des tâches du personnel'
            },
            xAxis: {
                categories: ['Admin']
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Total des tâches'
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    }
                }
            },
            legend: {
                align: 'right',
                x: -30,
                verticalAlign: 'top',
                y: 25,
                floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                borderColor: '#CCC',
                borderWidth: 1,
                shadow: false
            },
            tooltip: {
                headerFormat: '<b>{point.x}</b><br/>',
                pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                    }
                }
            },
            series: [{
                name: 'Tâches rélisées',
                data: [<?= $adminTasksDoneNumber ?>]
            }, {
                name: 'Tâches non réalisées',
                data: [<?= $adminTasksNotDoneNumber ?>]
            }]
        });
    </script>
</body>
</html>
<?php
}
else{
    header('Location:index.php');    
}
?>