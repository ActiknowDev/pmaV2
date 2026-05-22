<!-- start events calender -->
<link rel="stylesheet" href="fullcalendar/fullcalendar.min.css" />
<link rel="stylesheet" href="fullcalendar/menual.css" />
<script src="fullcalendar/lib/jquery.min.js"></script>
<script src="fullcalendar/lib/moment.min.js"></script>
<script src="fullcalendar/fullcalendar.min.js"></script>
<script>

    $(document).ready(function () {
        var calendar = $('#calendar').fullCalendar({
            editable: true,
            events: 'holidays/fetch-holidays',
            displayEventTime: false,
            eventRender: function (event, element, view) {
                if (event.allDay === 'true') {
                    event.allDay = true;
                } else {
                    event.allDay = false;
                }
            },
            selectable: true,
            selectHelper: true,
            select: function (start, end, allDay) {
                // $('#eventModal').modal('show');
                var title = prompt('Holiday Title');

                // $('#saveEventBtn').on('click', function() {
                    // var title = $('#eventTitle').val();
                    if (title) {
                        var startFormatted = moment(start).format("YYYY-MM-DD HH:mm:ss");
                        var endFormatted = moment(end).format("YYYY-MM-DD HH:mm:ss");

                        var csrfToken = '<?= $this->request->getAttribute('csrfToken'); ?>';

                        $.ajax({
                            url: 'holidays/add-holidays',
                            type: "POST",
                            headers: {
                                'X-CSRF-Token': csrfToken
                            },
                            data: {
                                title: title,
                                start: startFormatted,
                                end: endFormatted
                            },
                            success: function (data) {
                                displayMessage("Added Successfully");
                                setTimeout(function () {
                                    location.reload();
                                }, 1500); // 1.5 seconds
                            }
                        });

                        // $('#eventModal').modal('hide');
                        // $('#eventTitle').val('');

                        // calendar.fullCalendar('renderEvent',
                        // {
                        //     title: title,
                        //     start: start,
                        //     end: end,
                        //     allDay: allDay
                        // },
                        // true
                        // );
                    }
                    calendar.fullCalendar('unselect');
                    calendar.fullCalendar('refetchEvents');
                // });
            },

            editable: true,
            eventDrop: function (event, delta) {
                        var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                        var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                        var csrfToken = '<?= $this->request->getAttribute('csrfToken'); ?>';
                        $.ajax({
                            url: 'holidays/edit-holidays',
                            headers: {
                                'X-CSRF-Token': csrfToken
                            },
                            data: 'title=' + event.title + '&start=' + start + '&end=' + end + '&id=' + event.id,
                            type: "POST",
                            success: function (response) {
                                displayMessage("Updated Successfully");
                            }
                        });
                    },
            eventClick: function (event) {
                var deleteMsg = confirm("Do you really want to delete?");
                if (deleteMsg) {
                    $.ajax({
                        type: "get",
                        url: "holidays/delete-holidays",
                        data: "id=" + event.id,
                        success: function (response) {
                            if(parseInt(response) > 0) {
                                $('#calendar').fullCalendar('removeEvents', event.id);
                                displayMessage("Deleted Successfully");
                            }
                        }
                    });
                }
            }

        });
    });
    // $('#eventModal').on('hidden.bs.modal', function () {
    //     console.log('check');
    //     calendar.fullCalendar('unselect');
    // });

    function displayMessage(message) {
          $(".response").html("<div class='success'>"+message+"</div>");
        setInterval(function() { $(".success").fadeOut(); }, 1000);
    }
    $(document).on('click', '.close', function() {
        $('#eventModal').modal('hide');
    });
</script>

<style type="text/css">
    #calendar {
    }

    .response {
        height: 60px;
    }

    .success {
        background: #cdf3cd;
        padding: 10px 60px;
        border: #c3e6c3 1px solid;
        display: inline-block;
    }
    .fc button,
    .fc table,
    body .fc {
        font-size: 15px !important;
    }
</style>
<!-- end events calender -->
<style type="text/css">
    .res {
        text-align: center;
        color: #46bd55;
        font-size: 18px;
    }
</style>
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <!-- <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
          <div class="breadcrumb-title pe-3">Holidays</div>
          <div class="ps-3">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Holidays List</li>
              </ol>
            </nav>
          </div>
          
        </div>  -->
        <!--end breadcrumb-->

        <!--end row-->
                <div class="row">
                    <div class="col-12 col-md-3"></div>
                    <div class="col-12 col-md-6">
                    <!-- <div id="response" class="response"></div> -->
                        <div id="calendar"></div>
                    </div>
                </div>
        <!--end row-->
    </div>
</div>

<!-- Button to trigger modal -->
<!-- <button id="openModalBtn" type="button" class="btn btn-primary">Add Event</button> -->

<!-- Modal -->
<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eventModalLabel">Add Holiday</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="text" id="eventTitle" class="form-control" placeholder="Enter Holiday Title">
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
        <button id="saveEventBtn" type="button" class="btn btn-primary">Save Holiday</button>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="assets/js/bootstrap.bundle.min.js"></script>
<!--plugins-->

<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>

<script src="assets/js/app.js"></script>