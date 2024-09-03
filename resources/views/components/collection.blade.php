<!-- resources/views/components/collection.blade.php -->
<div class="flex justify-between p-4 items-center bg-blue-500 text-white rounded-lg border-2 border-white">
  <div>{{ $slot }}</div>
  
  <div class="flex space-x-2">
    <div>
      <a href="{{ route('song.show', $id) }}" class="btn bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
        詳細
      </a>
    </div>
  
    <div>
      <form action="{{ url('song/'.$id) }}" method="POST">
        @csrf
        @method('DELETE')
        
        <button type="submit" class="btn bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">
          削除
        </button>
      </form>
    </div>
  </div>
</div>