// Dashboard 1 Morris-chart

//var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

//Morris.Area({
//    element: 'morris-area-chart',
//    data: [{
//        m: '2015-01',
//        a: 0,
//        b: 270
//    }, {
//        m: '2015-02',
//        a: 54,
//        b: 256
//    }, {
//        m: '2015-03',
//        a: 243,
//        b: 334
//    }, {
//        m: '2015-04',
//        a: 206,
//        b: 282
//    }, {
//        m: '2015-05',
//        a: 161,
//        b: 58
//    }, {
//        m: '2015-06',
//        a: 187,
//        b: 0
//    }, {
//        m: '2015-07',
//        a: 210,
//        b: 0
//    }, {
//        m: '2015-08',
//        a: 204,
//        b: 0
//    }, {
//        m: '2015-09',
//        a: 224,
//        b: 0
//    }, {
//        m: '2015-10',
//        a: 301,
//        b: 0
//    }, {
//        m: '2015-11',
//        a: 262,
//        b: 0
//    }, {
//        m: '2015-12',
//        a: 199,
//        b: 0
//    },],
//    xkey: 'm',
//    ykeys: ['a', 'b'],
//    labels: ['2014', '2015'],
//    xLabelFormat: function (x) { // <-- changed
//        console.log("this is the new object:" + x);
//        var month = months[x.x];
//        return month;
//    },
//});



var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

Morris.Line({
    element: 'morris-area-chart',
    data: [{
        m: '2015-01', // <-- valid timestamp strings
        a: 0,
        b: 270
    }, {
        m: '2015-02',
        a: 54,
        b: 256
    }, {
        m: '2015-03',
        a: 243,
        b: 334
    }, {
        m: '2015-04',
        a: 206,
        b: 282
    }, {
        m: '2015-05',
        a: 161,
        b: 58
    }, {
        m: '2015-06',
        a: 187,
        b: 0
    }, {
        m: '2015-07',
        a: 210,
        b: 0
    }, {
        m: '2015-08',
        a: 204,
        b: 0
    }, {
        m: '2015-09',
        a: 224,
        b: 0
    }, {
        m: '2015-10',
        a: 301,
        b: 0
    }, {
        m: '2015-11',
        a: 262,
        b: 0
    }, {
        m: '2015-12',
        a: 199,
        b: 0
    },],
    xkey: 'm',
    ykeys: ['a', 'b'],
    labels: ['2014', '2015'],
    lineWidth: 1,
    hideHover: 'auto',
    xLabelFormat: function (x) { // <--- x.getMonth() returns valid index
        var month = months[x.getMonth()];
        return month;
    },
    dateFormat: function (x) {
        var month = months[new Date(x).getMonth()];
        return month;
    },
});


//Morris.Area({
//        element: 'morris-area-chart',
//        data: [{
//            period: '2017-01',
//            iphone: 50,
//            ipad: 80,
//            itouch: 20
//        }, {
//                period: '2017-02',
//            iphone: 130,
//            ipad: 100,
//            itouch: 80
//        }, {
//                period: '2017-03',
//            iphone: 80,
//            ipad: 60,
//            itouch: 70
//        }, {
//                period: '2017-04',
//            iphone: 70,
//            ipad: 200,
//            itouch: 140
//        }, {
//                period: '2017-05',
//            iphone: 180,
//            ipad: 150,
//            itouch: 140
//        }, {
//                period: '2017-06',
//            iphone: 105,
//            ipad: 100,
//            itouch: 80
//        },
//         {
//            period: '7',
//            iphone: 250,
//            ipad: 150,
//            itouch: 200
//        }],
//        xkey: 'period',
//        ykeys: ['iphone', 'ipad', 'itouch'],
//        labels: ['iPhone', 'iPad', 'iPod Touch'],
//        pointSize: 3,
//        fillOpacity: 0,
//        pointStrokeColors:['#00bfc7', '#fdc006', '#2c5ca9'],
//        behaveLikeLine: true,
//        gridLineColor: '#e0e0e0',
//        lineWidth: 1,
//        hideHover: 'auto',
//        lineColors: ['#00bfc7', '#fdc006', '#2c5ca9'],
//        resize: true
        
//    });

Morris.Area({
        element: 'morris-area-chart2',
        data: [{
            period: '2010',
            SiteA: 0,
            SiteB: 0,
            
        }, {
            period: '2011',
            SiteA: 130,
            SiteB: 100,
            
        }, {
            period: '2012',
            SiteA: 80,
            SiteB: 60,
            
        }, {
            period: '2013',
            SiteA: 70,
            SiteB: 200,
            
        }, {
            period: '2014',
            SiteA: 180,
            SiteB: 150,
            
        }, {
            period: '2015',
            SiteA: 105,
            SiteB: 90,
            
        },
         {
            period: '2016',
            SiteA: 250,
            SiteB: 150,
           
        }],
        xkey: 'period',
        ykeys: ['SiteA', 'SiteB'],
        labels: ['Site A', 'Site B'],
        pointSize: 0,
        fillOpacity: 0.4,
        pointStrokeColors:['#b4becb', '#00b5c2'],
        behaveLikeLine: true,
        gridLineColor: '#e0e0e0',
        lineWidth: 0,
        smooth: false,
        hideHover: 'auto',
        lineColors: ['#b4becb', '#00b5c2'],
        resize: true
        
    });


// LINE CHART
        var line = new Morris.Line({
          element: 'morris-line-chart',
          resize: true,
          data: [
            {y: '2011 Q1', item1: 2666},
            {y: '2011 Q2', item1: 2778},
            {y: '2011 Q3', item1: 4912},
            {y: '2011 Q4', item1: 3767},
            {y: '2012 Q1', item1: 6810},
            {y: '2012 Q2', item1: 5670},
            {y: '2012 Q3', item1: 4820},
            {y: '2012 Q4', item1: 15073},
            {y: '2013 Q1', item1: 10687},
            {y: '2013 Q2', item1: 8432}
          ],
          xkey: 'y',
          ykeys: ['item1'],
          labels: ['Item 1'],
          gridLineColor: '#eef0f2',
          lineColors: ['#a3a4a9'],
          lineWidth: 1,
          hideHover: 'auto'
        });
 // Morris donut chart
        
    Morris.Donut({
        element: 'morris-donut-chart',
        data: [{
            label: "Download Sales",
            value: 12,

        }, {
            label: "In-Store Sales",
            value: 30
        }, {
            label: "Mail-Order Sales",
            value: 20
        }],
        resize: true,
        colors:['#99d683', '#13dafe', '#6164c1']
    });

// Morris bar chart
    Morris.Bar({
        element: 'morris-bar-chart',
        data: [{
            y: '2006',
            a: 100,
            b: 90,
            c: 60
        }, {
            y: '2007',
            a: 75,
            b: 65,
            c: 40
        }, {
            y: '2008',
            a: 50,
            b: 40,
            c: 30
        }, {
            y: '2009',
            a: 75,
            b: 65,
            c: 40
        }, {
            y: '2010',
            a: 50,
            b: 40,
            c: 30
        }, {
            y: '2011',
            a: 75,
            b: 65,
            c: 40
        }, {
            y: '2012',
            a: 100,
            b: 90,
            c: 40
        }],
        xkey: 'y',
        ykeys: ['a', 'b', 'c'],
        labels: ['A', 'B', 'C'],
        barColors:['#b8edf0', '#b4c1d7', '#fcc9ba'],
        hideHover: 'auto',
        gridLineColor: '#eef0f2',
        resize: true
    });
// Extra chart
 Morris.Area({
        element: 'extra-area-chart',
        data: [{
                    period: '2010',
                    iphone: 0,
                    ipad: 0,
                    itouch: 0
                }, {
                    period: '2011',
                    iphone: 50,
                    ipad: 15,
                    itouch: 5
                }, {
                    period: '2012',
                    iphone: 20,
                    ipad: 50,
                    itouch: 65
                }, {
                    period: '2013',
                    iphone: 60,
                    ipad: 12,
                    itouch: 7
                }, {
                    period: '2014',
                    iphone: 30,
                    ipad: 20,
                    itouch: 120
                }, {
                    period: '2015',
                    iphone: 25,
                    ipad: 80,
                    itouch: 40
                }, {
                    period: '2016',
                    iphone: 10,
                    ipad: 20,
                    itouch: 30
                }


                ],
                lineColors: ['#f75b36', '#00b5c2', '#8698b7'],
                xkey: 'period',
                ykeys: ['iphone', 'ipad', 'itouch'],
                labels: ['Site A', 'Site B', 'Site C'],
                pointSize: 0,
                lineWidth: 0,
                resize:true,
                fillOpacity: 0.8,
                behaveLikeLine: true,
                gridLineColor: '#e0e0e0',
                hideHover: 'auto'
        
    });