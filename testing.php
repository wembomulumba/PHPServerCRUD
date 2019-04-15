<html ng-app="ionicApp">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
    
    <title>Tabs Example</title>

    <!-- LOAD JQUERY -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>

    <link href="lib/ionic-v1.2.4/css/ionic.css" rel="stylesheet">
    <script src="lib/ionic-v1.2.4/js/ionic.bundle.min.js"></script>

    <!--App Scripts-->
    <link rel="stylesheet" type="text/css" href="css/table.css">
    <script type="text/javascript" src="routes.js"></script>
    <script type="text/javascript" src="CRUD/student/controller.js"></script>
    <script type="text/javascript" src="CRUD/insert_student/insertController.js"></script>
    <script type="text/javascript" src="CRUD/update_student/updateController.js"></script>
    <script type="text/javascript" src="CRUD/delete_student/deleteController.js"></script>

    <script type="text/javascript" src="student-academic-performances/average_gpa/avgGPACtrl.js"></script>

  </head>

  <body>
    
    <ion-nav-bar class="bar-positive">
      <ion-nav-back-button>
      </ion-nav-back-button>
    </ion-nav-bar>
             
    <ion-nav-view></ion-nav-view>

    <script id="templates/facts2.html" type="text/ng-template">
      <ion-view view-title="Also Factual">
        <ion-content class="padding">
          <p>111,111,111 x 111,111,111 = 12,345,678,987,654,321</p>
          <p>1 in every 4 Americans has appeared on T.V.</p>
          <p>11% of the world is left-handed.</p>
          <p>1 in 8 Americans has worked at a McDonalds restaurant.</p>
          <p>$283,200 is the absolute highest amount of money you can win on Jeopardy.</p>
          <p>101 Dalmatians, Peter Pan, Lady and the Tramp, and Mulan are the only Disney cartoons where both parents are present and don't die throughout the movie.</p>
          <p>
            <a class="button icon ion-home" href="#/tab/home"> Home</a>
            <a class="button icon ion-chevron-left" href="#/tab/facts"> Scientific Facts</a>
          </p>
        </ion-content>
      </ion-view>
    </script>

    <script id="templates/about.html" type="text/ng-template">
      <ion-view view-title="About">
        <ion-content class="padding">

            <div ng-controller="studentAcademicController">
                <form method="post">
                    <div class="list">
                        <label class="item item-input">
                            <input type="text" ng-model="SID" placeholder="SID">
                        </label>
                    </div>

                    <div class="padding">
                        <input class="button button-block button-positive activated" value="submit" ng-click="getGPA()"/>
                    </div>
                </form>
                <p>
                    Testing
                </p>
                <div ng-repeat="x in SID_and_GPA">
                    <p>Student ID: {{x.SID }}</P>
                    <p>GPA: {{x.GPA}}</P>
                    <p>Total Credits: {{x.cumulative_credit}}</p>
                    <p>Academic Status: {{x.passing_status}}</p>
                    <p>Reason: {{x.reason}}</p>
                </div>

            </div>

        </ion-content>
      </ion-view>
    </script>

    <script id="templates/nav-stack.html" type="text/ng-template">
      <ion-view view-title="Tab Nav Stack">
        <ion-content class="padding">
          <p><img src="http://ionicframework.com/img/diagrams/tabs-nav-stack.png" style="width:100%"></p>
        </ion-content>
      </ion-view>
    </script>

    <script id="templates/contact.html" type="text/ng-template">
      <ion-view title="Contact">
        <ion-content>
          <div class="list">
            <div class="item">
              @IonicFramework
            </div>
            <div class="item">
              @DriftyTeam
            </div>
          </div>
        </ion-content>
      </ion-view>
    </script>

  </body>
</html>
