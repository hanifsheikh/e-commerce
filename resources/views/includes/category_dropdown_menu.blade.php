<div class="container hide mx-auto px-3 xl:px-10" id="category-dropdown-menu">
    <div class="relative flex w-full">
        <div class="absolute z-10 flex w-full">
            <div class="flex w-full bg-white shadow-xl rounded-b-lg p-10">
                <!-- <div id="category-dropdown-menu" class="flex  w-full bg-white shadow-xl rounded-b-lg p-10"> -->
                <div class="flex w-full space-x-5">
                    <ul class="flex lg:w-1/4 xl:1/6 flex-col space-y-1">
                        @foreach($categories as $parent) <li class="dropdown-item-parent flex space-x-2">
                            <a href="/category/{{$parent->category_url}}" class="flex text-base text-gray-600 hover:text-white bg-transparent rounded-sm px-3 py-1 hover:bg-orange transition duration-100 ease-in">{{$parent->category_name}}</a>
                            <ul id="second-level-children" class="hidden grid  grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-5 gap-5 w-full pr-5 overflow-y-auto">
                                @if(count($parent->childrens))
                                @foreach($parent->childrens as $second)<li class="flex flex-col space-y-2">
                                    <a href="/category/{{$second->category_url}}" class="flex font-bold text-base text-dark hover:text-orange transition duration-300 ease-out-expo">{{$second->category_name}}</a>
                                    <ul id="third-level-children" class="flex flex-col space-y-2">
                                        @if(count($second->childrens))
                                        @foreach($second->childrens as $third)
                                        <li class="flex">
                                            <a href="/category/{{$third->category_url}}" class="flex text-sm text-gray hover:text-orange transition duration-300 ease-out-expo">{{$third->category_name}}</a>
                                        </li>
                                        @endforeach
                                        @endif
                                    </ul>
                                </li>
                                @endforeach
                                @endif
                            </ul>
                        </li>
                        @endforeach
                    </ul>
                    <div class="flex border-r border-light-gray"></div>
                    <div id="children-placeholder" class="flex lg:w-3/4 xl:w-5/6" style="height:500px;max-height: 90%">
                        <ul>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>