
    // var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
    //     cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
    //     forceTLS: true
    // });

var channel = pusher.subscribe("event.{{ $event->id }}");

channel.bind("CheckInUpdated", function(data) {

    document.getElementById("count").innerText = data.checked_in_count;
    document.getElementById("remaining").innerText = data.remaining_capacity;

    let badge = document.getElementById("status-badge");
    if (data.remaining_capacity > 0) {
        badge.innerText = "Available";
        badge.classList.remove("full-badge");
        badge.classList.add("badge");
    } else {
        badge.innerText = "Full";
        badge.classList.remove("badge");
        badge.classList.add("full-badge");
    }

    if (data.last_checked_in) {
        let list = document.getElementById("recent");

        let item = document.createElement("li");
        item.innerText = data.last_checked_in.name + " (" + data.last_checked_in.email + ")";
        list.prepend(item);

        if (list.children.length > 5) {
            list.removeChild(list.lastChild);
        }
    }

});