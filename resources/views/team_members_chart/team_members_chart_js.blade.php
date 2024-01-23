<script>
    $(document).ready(function() {
        // Toggle visibility of subordinates_list when clicking on the name
        // $('.mb-2.mt-1.employee_subordinates_list').click(function() {
        //     $(this).siblings('.subordinates_list').toggle();
        // });
        $('.mb-2.mt-1.employee_subordinates_list.senior_hr_manager, .mb-2.mt-1.employee_subordinates_list.director_card, .mb-2.mt-1.employee_subordinates_list.business_development_l2_card').click(function() {
            $(this).siblings('.subordinates_list').toggle();
        });

        // Toggle visibility of sub_subordinates_list when clicking on sub_ordinate_employee_name
        $('.subordinate_employee_details').click(function() {
            var $subordinate_list = $(this).next('.sub_subordinates_list');
            $subordinate_list.toggle();
            var $icon = $(this).find('i');
            $icon.toggleClass('fa-circle-arrow-down fa-circle-arrow-up');
        });

        // Show icon on those who has any employee details
        $('.sub_ordinate_employee_name').each(function() {
            var $this = $(this);
            var $subordinatesList = $this.next('.sub_subordinates_list');

            if ($subordinatesList.children().length > 0) {
                $this.find('.detail_icon').show().css('margin-top', '10px');
            } else {
                $this.find('.detail_icon').hide();
            }
        });

    });

    // change i icon when click on employee_subordinates_list
    // var elements = document.querySelectorAll(".mb-2.mt-1.employee_subordinates_list");
    var elements = document.querySelectorAll(".mb-2.mt-1.employee_subordinates_list.senior_hr_manager, .mb-2.mt-1.employee_subordinates_list.director_card, .mb-2.mt-1.employee_subordinates_list.business_development_l2_card");
    elements.forEach(function(element) {
        element.addEventListener("click", function() {
            var arrowIcon = this.querySelector(".arrow_icon i");

            if (arrowIcon.classList.contains("fa-circle-arrow-down")) {
                arrowIcon.classList.remove("fa-circle-arrow-down");
                arrowIcon.classList.add("fa-circle-arrow-up");
            } else {
                arrowIcon.classList.remove("fa-circle-arrow-up");
                arrowIcon.classList.add("fa-circle-arrow-down");
            }
        });
    });
</script>

<script>
    // When employee search the text then this function call
    $(document).ready(function() {
        function filterNames(searchTerm) {
            searchTerm = searchTerm.toLowerCase();

            $('.subordinate_designation_name, .employee_subordinates_list').removeClass('name_found');

            if (searchTerm.trim() !== '') {
                $('.subordinate_designation_name, .employee_subordinates_list').filter(function() {
                    return $(this).text().toLowerCase().includes(searchTerm);
                }).show().addClass('name_found');
                $('.subordinates_list').hide();

                $('.name_found a').trigger('click');
            } else {
                $('.sub_subordinates_list, .subordinates_list').hide();
            }
        }

        $('#employee_name_search').on('keyup', function (e) {
            var searchTerm = $(this).val();
            filterNames(searchTerm);
        });

        // $('#employee_name_search').on('keyup', function(e) {
        //     if (e.keyCode === 13) {
        //         var searchTerm = $(this).val();
        //         filterNames(searchTerm);
        //     }
        // });
    });
</script>

<script>
    // for moving horizontally when hold the mouse 
    let mouseDown = false;
    let startX, scrollLeft;
    const slider = document.querySelector('.container-wrap');

    const startDragging = (e) => {
        mouseDown = true;
        startX = e.pageX - slider.offsetLeft;
        scrollLeft = slider.scrollLeft;
    }

    const stopDragging = (e) => {
        mouseDown = false;
    }

    const move = (e) => {
        e.preventDefault();
        if (!mouseDown) {
            return;
        }
        const x = e.pageX - slider.offsetLeft;
        const scroll = x - startX;
        slider.scrollLeft = scrollLeft - scroll;
    }

    // Add the event listeners
    slider.addEventListener('mousemove', move, false);
    slider.addEventListener('mousedown', startDragging, false);
    slider.addEventListener('mouseup', stopDragging, false);
    slider.addEventListener('mouseleave', stopDragging, false);
</script>
