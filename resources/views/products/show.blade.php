@extends('products.layout')

@section('content')

    <h1>Preview Post</h1>

    <table>
        <tbody>
            <tr>
                <th>
                    Title
                </th>
                <td>
                    {{ $product->name }}
                </td>
            </tr>
            <tr>
                <th>
                    Description
                </th>
                <td>
                    {{ $product->description }}
                </td>
            </tr>
            <tr>
                <th>
                    Price
                </th>
                <td>
                    {{ $product->price }}
                </td>
            </tr>
            <tr>
                <th>
                </th>
                <td>

                    {{-- Show each of the attached media library items - a clickable thumbnail which links to the large version --}}
                    @foreach ($product->media as $mediaItem)
                        
                        <a href="{{ route('api.media.show', ['mediaItem' => $mediaItem->id, 'size' => 'large']) }}">
                            <img src="{{ route('api.media.show', ['mediaItem' => $mediaItem->id, 'size' => 'thumb']) }}">
                        </a>
                        <p>
                            {{$mediaItem->name}}
                        </p>

                    @endforeach
                    <hr/>
                </td>
            </tr>
        </tbody>
    </table>

@endsection