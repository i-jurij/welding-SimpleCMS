<?php
$title = 'List of logs';
$page_meta_description = 'List of logs';
$page_meta_keywords = '';
$robots = 'NOINDEX, NOFOLLOW';
?>

@extends("layouts/index_admin")
@section("content")
<style>
    /*
    .main {
        position: relative;
        padding: 1rem 1rem 3rem;
        min-height: calc(100vh - 20rem);
    }

    .pagination-container {
        width: calc(100% - 2rem);
        display: flex;
        align-items: center;
        position: absolute;
        bottom: 0;
        padding: 1rem 0;
        justify-content: center;
    }
    */
    .pagination-number,
    .pagination-button {
        font-size: 1.1rem;
        box-shadow: var(--boxshadow);
        border: none;
        margin: 0.25rem 0.25rem;
        cursor: pointer;
        height: 2.5rem;
        width: 2.5rem;
        border-radius: .2rem;
    }

    .pagination-number:hover,
    .pagination-button:not(.disabled):hover {
        background: var(--bgcolor-button-active);
    }

    .pagination-number.active {
        background: var(--bgcolor-button-active);
    }
</style>
<div class="content main">
    @if (!empty(session('res')))
        @if (is_array(session('res')))
            @foreach (session('res') as $mes)
                {{$mes}}<br>
            @endforeach
        @elseif (is_string(session('res')))
            <p class="pad">{{session('res')}}</p>
        @endif
    @endif
    @if (!empty($list) && is_array($list))
        <form action="{{url()->route('admin.logs.show')}}" method="post" enctype="application/x-www-form-urlencoded" id="" class="form_radio_btn">
        @csrf
            @foreach ($list as $value)
            <label >
                <input type="radio" name="log_name" value="{{$value['file']}}" />
                <span>{{Crypt::decryptString($value['file'])}}<br>{{$value['size']}}</span>
            </label>
            @endforeach
            <p class="pad">
                <button type="submit" class="buttons" name="show" value="show" >Show</button>
                <button type="submit" class="buttons" name="clear" value="clear" >Clear</button>
            </p>
        </form>
    @endif

    <?php
        if (!empty($show) && is_array($show)) {
            echo '<p class="text_center pad">Файл: <b>"'.$show['log_name'].'"</b></p>';
            ?>
    <div>
        <div id="list" class="logs"></div>
        <nav class="pagination-container margin_top_1rem">
            <span id="pagination-numbers"></span>
        </nav>
    </div>

    <?php
        } elseif (!empty($show) && is_string($show)) {
            echo '<p>'.$show.'</p>';
        }
?>
</div>

<script>
    var list = <?php if (!empty($show) && is_array($show) && !empty($show['log_data'])) {
        echo json_encode($show['log_data']);
    } else {
        echo json_encode([]);
    } ?>;
    //console.log(list)
    //var list = new Array();
    var pageList = new Array();
    var currentPage = 1;
    var numberPerPage = 30;
    var numberOfPages = 0;
    var number_of_page_for_show_in_pag = 5;

    const paginationNumbers = document.getElementById("pagination-numbers");
    const div_list = document.getElementById("list");
    const nextButton = document.getElementById("next");
    const prevButton = document.getElementById("previous");
    const firstButton = document.getElementById("first");
    const lastButton = document.getElementById("last");
    const pag_nums = document.querySelectorAll(".pagination-number");

    numberOfPages = getNumberOfPages();

    function getNumberOfPages() {
        return Math.ceil(list.length / numberPerPage);
    }

    function pageArray() {
        page_array = [];
        numberOfPages = getNumberOfPages();
        for (let i = 1; i <= numberOfPages; i++) {
            page_array[i] = [];
            for (let ind = (i - 1) * numberPerPage; ind < i * numberPerPage; ind++) {
                if (list[ind]) {
                    page_array[i].push(list[ind]);
                }
            }
        }
        return page_array;
    }

    var pageArray = pageArray();
    //console.log(pageArray)

    function loadList() {
        drawList();
        getPaginationNumbers();
        handleActivePageNumber();
        handlePageButtonsStatus();
    }

    function drawList() {
        if (div_list) {
            div_list.innerHTML = "";
            if (pageArray[currentPage]) {
                for (let r = 0; r < pageArray[currentPage].length; r++) {
                    div_list.innerHTML += '<p class="text_left">'+pageArray[currentPage][r] + "</p>";
                }
            }
        }
    }

    function nextPage(numberOfPages) {
        if (currentPage <= numberOfPages) { currentPage += 1; loadList(); }
    }

    function previousPage() {
        if (currentPage > 1) { currentPage -= 1; loadList(); }
    }
    /*
    function firstPage() {
        currentPage = 1;
        loadList();
    }

    function lastPage() {
        currentPage = numberOfPages;
        loadList();
    }
    */
    function handlePageButtonsStatus() {
        if (nextButton) {
            nextButton.disabled = currentPage == numberOfPages ? true : false;
        }
        if (prevButton) {
            prevButton.disabled = currentPage == 1 ? true : false;
        }
        if (firstButton) {
            firstButton.disabled = currentPage == 1 ? true : false;
        }
        if (lastButton) {
            lastButton.disabled = currentPage == numberOfPages ? true : false;
        }
    }

    const handleActivePageNumber = () => {
        document.querySelectorAll(".pagination-number").forEach((button) => {
            button.classList.remove("active");
            const pageIndex = Number(button.getAttribute("page-index"));
            if (pageIndex == currentPage) {
                button.classList.add("active");
            }
        });
    };

    let pag_arr = [];

    function appendPageNumber (index) {
        let ins = '<button class="pagination-number" page-index="'+index+'" aria-label="Page'+index+'">'+index+'</button>';
        pag_arr.push(ins);
    };

    function drawPaginator() {
        //paginationNumbers.innerHTML += '<input type="button" id="first" onclick="firstPage();window.scrollTo(0,0);" value="first" class="pagination-button" />';
        paginationNumbers.innerHTML += '<input type="button" id="previous" onclick="previousPage();window.scrollTo(0,0);" value="&lt;" class="pagination-button" />';
        pag_arr.forEach((value) => {
            paginationNumbers.innerHTML += value;
        });
        paginationNumbers.innerHTML += '<input type="button" id="next" onclick="nextPage();window.scrollTo(0,0);" value="&gt;" class="pagination-button" />';
        //paginationNumbers.innerHTML += '<input type="button" id="last" onclick="lastPage();window.scrollTo(0,0);" value="last" class="pagination-button" />';
    }

    const getPaginationNumbers = () => {
        if (paginationNumbers && numberOfPages > 1) {
            paginationNumbers.innerHTML = '';
            let first_show = 1;
            let end_show = numberOfPages;
            let points_show = '...';
            let current_start_show = 0;
            let current_end_show = 0;
            pag_arr = [];

            if (numberOfPages < number_of_page_for_show_in_pag) {
                for (let index = 1; index <= numberOfPages; index++) {
                        appendPageNumber(index, pag_arr);
                }
            } else {
                appendPageNumber(first_show, pag_arr);
                if (currentPage >= 1 && currentPage < number_of_page_for_show_in_pag) {
                    for (let index = 2; index <= number_of_page_for_show_in_pag; index++) {
                        appendPageNumber(index, pag_arr);
                    }
                    appendPageNumber('...', pag_arr);
                }
                if (currentPage > (number_of_page_for_show_in_pag - 1) && currentPage <= (numberOfPages - number_of_page_for_show_in_pag)) {
                    appendPageNumber('...', pag_arr);
                    /*
                    for (let ind = currentPage; ind < currentPage + (number_of_page_for_show_in_pag - 1); ind++) {
                        appendPageNumber(ind, pag_arr);
                    }
                    */
                    current_start_show = Number(Number(currentPage) - Math.round(number_of_page_for_show_in_pag / 2)) + 1;
                    current_end_show = Number(Number(currentPage) + Math.round(number_of_page_for_show_in_pag / 2)) ;
                    for (let ind = current_start_show; ind < current_end_show; ind++) {
                        appendPageNumber(ind, pag_arr);
                    }
                    appendPageNumber('...', pag_arr);
                }
                if (currentPage > (numberOfPages - number_of_page_for_show_in_pag)) {
                    appendPageNumber('...', pag_arr);
                    for (let i = (numberOfPages - number_of_page_for_show_in_pag); i < numberOfPages; i++) {
                        appendPageNumber(i, pag_arr);
                    }
                }
                appendPageNumber(end_show, pag_arr);
            }

            //console.log(pag_arr)
            drawPaginator();
        }
    };


    const butNumberClick = () => {
        if (paginationNumbers) {
            paginationNumbers.addEventListener("click", (element) => {
                if (element.target.classList.contains('pagination-number') && element.target.getAttribute('page-index') !== '...' ) {
                    pageIndex = Number(element.target.getAttribute('page-index'));
                    currentPage = pageIndex;
                    //console.log(currentPage)
                    loadList();
                    window.scrollTo(0,0);
                }
            });
        }
    };

    function load() {
        loadList();
        butNumberClick();
    }

    window.onload = load;
</script>



@stop
