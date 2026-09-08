@extends('index')
@section('statistics')
@section('content')

<section>
    <div class="container mt-4">

        <form id="form">
            <div class="input-group">
                <div class="form-outline">
                    <input type="search" id="text-to-search" name="text-to-search" class="form-control" />
                    <label class="form-label" for="text-to-search">Search</label>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>

            </div>

        </form>
    </div>


    <div class="container">
        <div class="row" style="justify-content: center; ">
            <div class="card text-center" id="repositoriesCard" style="display: none;max-width: 73%;">
                <div class="card-body">
                    <div class="d-flex  justify-content-center">
                        <h3>Scopus</h3>
                        <div class="spinner-border" role="status" id="scopus-spinner" aria-hidden="true" style="margin-left:0.5rem"></div>
                        <i class="far fa-check-circle fa-2x" id="scopus-check-icon" style="color: #00ff40;display: none;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="justify-content: center; padding-top: 3%;">
            <div class="col-xl-9">
                <!-- "-->
                <div class="card mb-2" id="search-results" style="display: none;">

                    <div class="card-body" id="element1">

                    </div>
                    <!-- Container for demo purpose -->
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    const apiResultsToShow = document.getElementById("search-results");

    const repositoriesCard = document.getElementById("repositoriesCard");

    window.addEventListener("load", (event) => {

        const apiResultsCardAnimation = new mdb.Animate(apiResultsToShow, {
            animation: "slide-in-up",
            animationStart: "manually",
            animationDelay: "0",
            animationDuration: "500",
            onEnd: () => {
                apiResultsCardAnimation.stopAnimation();
            },
            onStart: () => {
                if (apiResultsToShow.style.display != "block") apiResultsToShow.style.display = "block";
            }
        });

        const repositoriesCardAnimation = new mdb.Animate(repositoriesCard, {
            animation: "slide-in-left",
            animationStart: "manually",
            animationDelay: "0",
            animationDuration: "1000",
            onEnd: () => {
                repositoriesCardAnimation.stopAnimation();
            },
            onStart: () => {
                if (repositoriesCard.style.display != "block") repositoriesCard.style.display = "block";
            }
        });

        // Initializes the objects animations
        repositoriesCardAnimation.init();
        apiResultsCardAnimation.init();
    });

    $("#form").submit(function(event) {
        event.preventDefault();

        // Gets the object instance
        const repositoriesCardAnimation = mdb.Animate.getInstance(repositoriesCard);

        // start the first animation
        repositoriesCardAnimation.startAnimation();

        window.setTimeout(() => {

            const apiResultsCardAnimation = mdb.Animate.getInstance(apiResultsToShow);

            $.ajax({
                type: 'GET',
                data:{
                    doi: document.getElementById("text-to-search").value
                },
                url: 'https://portalcientifico.islagaia.pt/test-search',
                success: function(data, status) {

                    document.getElementById("scopus-spinner").style.display = "none";
                    document.getElementById("scopus-check-icon").style.display = "block";

                    const cardContent = document.getElementById("element1");

                    cardContent.innerHTML = `Title: ${data["data"][0]["dcTitle"]}<br>
                                            Doi: ${data["data"][0]["doi"]} <br>
                                            Url: <a href="${data["data"][0]["url"]}"  target="_blank" >clique</a> <br>
                                            ScopusId: ${data["data"][0]["dcIdentifier"]}<br>
                                            Eid: ${data["data"][0]["eid"]} <br>
                                            Creator:${data["data"][0]["dcCreator"]}<br>
                                            Publication Name: ${data["data"][0]["prismPublicationName"]}<br>
                                            Indexed: ${data["data"][0]["api"]}`;


                    apiResultsCardAnimation.startAnimation();
                }
            });

        }, 3000);




    });
</script>
@endsection