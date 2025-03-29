$(document).ready(function() {
    // search event
    $('#searchBtn').on('click', function() {
        const movieName = $('#movieSearch').val();
        const userId = $('.header').data('user-id');

        // input validate
        if (movieName.trim() === '') {
            alert('Please enter a movie name.');
            return;
        }

        // ajax request to the OMDB API to fetch movie details
        $.ajax({
            url: `https://www.omdbapi.com/?i=tt3896198&apikey=6c1fbfcc&t=${encodeURIComponent(movieName)}`,
            method: 'GET',
            success: function(data) {
                if (data.Response === 'True') {
                    // create the searched movie html div
                    const movieHTML = `
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 10px; padding: 15px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); max-width: 300px; margin: 10px auto; background-color: #f9f9f9;">
                        <h4 style="font-size: 20px; margin: 0;">${data.Title} (${data.Year})</h4>
                        <img src="${data.Poster}" alt="${data.Title}" style="width: 100%; max-width: 200px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);">
                        <button id="saveMovie" style="padding: 8px 15px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">Add to favourite</button>
                    </div>`;
                    $('#movieResult').html(movieHTML);

                    // save button event
                    $('#saveMovie').on('click', function() {
                        $.ajax({
                            url: 'save_movie.php',
                            method: 'POST',
                            data: {
                                title: data.Title,
                                poster: data.Poster,
                                userId: userId
                            },
                            beforeSend: function() {
                                $('#loader').removeClass('hidden');
                            },
                            success: function(response) {
                                location.reload(); // Reload on success
                            },
                            error: function(xhr) {
                                alert('Error: ' + xhr.responseText); 
                            },
                            complete: function() {
                                $('#loader').addClass('hidden');
                            }
                        });
                    });
                } else {
                    $('#movieResult').html('<p class="text-red-500">Movie not found!</p>');
                }
            }
        });
    });

    // Event listener for deleting a saved movie
    $('.delete-movie').click(function() {
        if (!confirm('Are you sure you want to delete this movie?')) return;
        let btn = $(this);
        let movieId = btn.data('id');
        $('#loader').removeClass('hidden');
        $.post('delete_movie.php', { movie_id: movieId }, function(response) {
            if (response.status === 'success') {
                $('#loader').addClass('hidden');
                location.reload();
            } else {
                alert(response.message); 
            }
        }, 'json');
    });
});
