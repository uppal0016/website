<style>
    .org-chart ul {
        padding-top: 20px;
        position: relative;
        transition: all 0.5s;
        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;
    }

    .org-chart li {
        float: left;
        text-align: center;
        list-style-type: none;
        position: relative;
        padding: 20px 5px 5px 5px;
        transition: all 0.5s;
        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;
        margin: 0 0 25px;
    }

    .org-chart li::before,
    .org-chart li::after {
        content: "";
        position: absolute;
        top: 0;
        right: 50%;
        border-top: 1px solid #ccc;
        width: 50%;
        height: 20px;
    }

    .org-chart li::after {
        right: auto;
        left: 50%;
        border-left: 1px solid #ccc;
    }

    .org-chart li:only-child::after,
    .org-chart li:only-child::before {
        display: none;
    }

    .org-chart li:only-child {
        padding-top: 0;
    }

    .org-chart li:first-child::before,
    .org-chart li:last-child::after {
        border: 0 none;
    }

    .org-chart li:last-child::before {
        border-right: 1px solid #ccc;
        border-radius: 0 5px 0 0;
        -webkit-border-radius: 0 5px 0 0;
        -moz-border-radius: 0 5px 0 0;
    }

    .org-chart li:first-child::after {
        border-radius: 5px 0 0 0;
        -webkit-border-radius: 5px 0 0 0;
        -moz-border-radius: 5px 0 0 0;
    }

    .org-chart ul ul::before {
        content: "";
        position: absolute;
        top: 0;
        left: 50%;
        border-left: 1px solid #ccc;
        width: 0;
        height: 20px;
    }

    .org-chart li a {
        border: 1px solid #ccc;
        padding: 10px;
        text-decoration: none;
        color: #333;
        font-family: arial, verdana, tahoma;
        font-size: 11px;
        display: inline-block;
        border-radius: 5px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        transition: all 0.5s;
        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;
        position: relative;
        cursor: pointer;
        min-height: 72px;
        display: inline-flex !important;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        margin-top: 0 !important;
    }

    .org-chart li a:hover,
    .org-chart li a:hover+ul li a,
    .org-chart li a:hover+a+ul li a {
        background: #c8e4f8;
        color: #000;
        border: 1px solid #94a0b4;
    }

    .org-chart li a:hover+ul li::after,
    .org-chart li a:hover+ul li::before,
    .org-chart li a:hover+ul::before,
    .org-chart li a:hover+ul ul::before,
    .org-chart li a:hover+ ul li.subordinate_designation_name:nth-child(5n) a:before,
    .org-chart li a:hover+ ul li.subordinate_designation_name a:after,
    .org-chart li a:hover+ ul li.subordinate_designation_name a b:before  {
        border-color: #808080;
    }

    .org_list {
        display: flex;
        overflow-x: scroll;
        padding: 100px 0px;
        align-items: center;
        justify-content: center;
    }

    .org-chart > .org_list > li .employee_name_list{
        grid-template-columns: auto auto auto auto auto auto auto;
    }
    
    .org-chart li .main_wrap a {
        position: relative;
    }

    .main_wrap a:first-child::before {
        content: "";
        position: absolute;
        top: 100%;
        border-left: 1px solid #ccc;
        width: 0;
        height: 20px;
        right: -3px;
    }

    .org-chart ul ul.sub_list::before {
        display: none;
    }

    /*********************/
    .org-chart {
        display: -webkit-box;
    }

    ul.org_list {
        text-align: center;
        margin: 0 auto;
        display: grid;
        grid-template-columns: auto auto auto auto auto;
        justify-content: center;
        overflow: auto;
    }

    .org-chart ul {
        display: grid;
        grid-template-columns: auto auto auto auto auto;
        justify-content: center;
        padding-left: 0;
    }

    .org-chart li a {
        min-width: 162px;
        box-shadow: 0px 2px 4px 1px rgba(0, 0, 0, 0.05);
    }

    /* .org-chart li.subordinate_designation_name:nth-child(5n+6) {
        top: auto;
        right: auto;
        left: 0;
        border-left: 1px solid #cccccc;
        bottom: auto;
    } */

    .org-chart li.subordinate_designation_name:nth-last-child(1),
    .org-chart li.subordinate_designation_name:nth-last-child(2),
    .org-chart li.subordinate_designation_name:nth-last-child(3),
    .org-chart li.subordinate_designation_name:nth-last-child(4),
    .org-chart li.subordinate_designation_name:nth-last-child(5) {
        border: none;
    }

    .org-chart li.subordinate_designation_name:nth-last-child(6) {
        border-right: none;
    }

    span.arrow_icon {
        position: absolute;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        left: 50%;
        transform: translateX(-50%);
        bottom: -8px;
        z-index: 99;
        cursor: pointer;
        color: #666;
        font-size: 16px;
        background: #fff;
        padding: 1px;
    }

    .org-chart li a.senior_software_engineer_l2_card {
        background: rgba(255, 99, 71, 0.1);
        border: 1px solid rgba(255, 99, 71, 1);
    }

    /***********************/
    .org-chart li a.director_card, .org-chart li a.director_hr_card {
        background-color: rgba(0, 180, 42, 0.1);
        border: 1px solid rgba(0, 180, 42, 1);
    }

    .org-chart li a.junior_software_engineer_card {
        background: rgba(22, 93, 255, 0.1);
        border: 1px solid rgba(22, 93, 255, 1);
    }

    .org-chart li a.business_development_l2_card {
        background: rgba(153, 84, 235, 0.1);
        border: 1px solid rgba(153, 84, 235, 1);
    }

    .org-chart li a.founder_card {
        background: rgba(5, 177, 197, 0.1);
        border: 1px solid rgba(5, 177, 197, 1);
    }

    .org-chart li a.manager_l2_card {
        background: rgba(188, 5, 197, 0.1);
        border: 1px solid rgba(188, 5, 197, 1);
    }

    .org-chart li a.manager_l1_card {
        background: rgba(181, 197, 5, 0.1);
        border: 1px solid rgba(181, 197, 5, 1);
    }

    .org-chart li a.software_engineer_l1_card {
        background: rgba(197, 5, 5, 0.1);
        border: 1px solid rgba(197, 5, 5, 1);
    }

    .org-chart li a.senior_software_engineer_l1_card {
        background: rgba(197, 75, 5, 0.1);
        border: 1px solid rgba(197, 75, 5, 1);
    }

    .org-chart li a.senior_manager_l1_card {
        background: rgba(130, 197, 5, 0.1);
        border: 1px solid rgba(130, 197, 5, 1);
    }

    .org-chart li a.software_engineer_l2_card {
        background: rgba(5, 197, 63, 0.1);
        border: 1px solid rgba(5, 197, 63, 1);
    }

    .subordinate_designation_name.name_found > a, .subordinate_designation_name.name_found > a:hover{
        background-color: #00b1c3 !important;
        color: #fff;
        font-size: 14px;
        border: 1px solid #00b1c3 !important;
    }
    .color-code{
        background: #e2e2e2;
        padding: 20px;
        / margin: 20px; /
    }
    .color-box{width: 10px;height: 10px;border-radius:20px;display: inline-block}
    .parent{background: rgba(5, 177, 197, 1);}
    .child1{background: rgba(0, 180, 42, 1)}
    .child2{background: rgba(153, 84, 235, 1)}
    .child5{background: rgba(130, 197, 5, 1)}
    .child6{background: rgba(188, 5, 197, 1)}
    .child7{background: rgba(181, 197, 5, 1)}
    .child8{background: rgba(255, 99, 71, 1)}
    .child9{background: rgba(197, 75, 5, 1)}
    .child10{background: rgba(5, 197, 63, 1)}
    .child11{background: rgba(197, 5, 5, 1)}
    .child12{background: rgba(22, 93, 255, 1)}
    .container-wrap{
        width:100%;
        overflow-x: hidden; 
        float:left;
    }
    .child-chart{
        font-size:15px;
        font-family:arial;
        padding:10px;
        cursor: pointer;
    }
    li.list-inline-item {
        min-width: 23%;
    }



    
    .org-chart li.subordinate_designation_name:nth-child(5n) a:before {
        border-right: 1px solid #cccccc;
        content: '';
        position: absolute;
        right: -7px;
        width: 1px;
        height: 103px;
        top: -21px;
    }
    
    .org-chart li.subordinate_designation_name a:after {
        border-bottom: 1px solid #cccccc;
        content: '';
        position: absolute;
        right: 0;
        width: calc(100% + 12px);
        bottom: -12px;
        left: -5px;
        margin: auto;
        z-index: 10;
    }
    
    .org-chart li.subordinate_designation_name:nth-child(5n+6) a b:before {
        content: '';
        width: 1px;
        height: 29px;
        border-left: 1px solid #cccccc;
        position: absolute;
        left: -16px;
        top: -68px;
        bottom: 0;
    }
    
    .org-chart li.subordinate_designation_name a b {
        position: relative;
        width: 100%;
        display: block;
        white-space:nowrap;
    }
    
    .org-chart li a br {display: none;}
    
    .main_wrap {
        display: inline-flex;
        gap: 2px;
    }
    
    .org-chart li.subordinate_designation_name:nth-last-child(-n+4) a:after {
    }

    .org-chart ul.subordinates_list:after {
        content: '';
        position: absolute;
        bottom: 20px;
        height: 12px;
        background: white;
        width: 102%;
        z-index: 10;
        left: -1%;
        right: 0;
        margin: auto;
    }
    .org-chart ul.sub_subordinates_list:after {
        content: '';
        position: absolute;
        bottom: 12px;
        height: 12px;
        background: white;
        width: 102%;
        z-index: 10;
        left: -1%;
        right: 0;
        margin: auto;
    }
    .org-chart ul.subordinates_list li.subordinate_designation_name:last-child a::before {
        display: none;
    }
    .org-chart ul.sub_subordinates_list li a b::before {
        border: none !important;
    }
    /* .org-chart ul.sub_subordinates_list li:nth-child(5n+6) a b::before {
        height: 21px;
        top: -58px;
    } */
    .org-chart ul.sub_subordinates_list li:nth-child(5n+6) a b::before {
    height: 21px;
    top: -60px;
    border-left: 1px solid #cccccc !important;
    border-left: 1px solid #cccccc !important;
    }
    .org-chart li.subordinate_designation_name.manager_card:nth-child(5n+6) ul.sub_subordinates_list a b:before {
        border: none;
    }


</style>