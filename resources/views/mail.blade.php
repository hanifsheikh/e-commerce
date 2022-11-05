<!DOCTYPE html>
<html>

<body>
    <div class="container mx-auto mt-10">
        <div class="flex justify-center">
            <h4> Hi, <b class="font-bold">{{$name}}!</b></h4>
        </div>
        <div>
            Please verify your email by clicking this link.
            <a target="_blank" href="{{$verification_link}}"> <b>{{$verification_link}}</b></a>
            </br>
            If link is not working, simply copy the link and paste in another browser tab.
        </div>
    </div>
</body>

</html>