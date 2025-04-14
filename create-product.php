<?php
include_once "include/header.php";


?>



<div class="container mt-5">
	<div class="row justify-content-center">
		<h2 class="mb-4">Add New Book</h2>
		<form action="insert_book.php" method="POST" class="needs-validation" novalidate>
		  <div class="row mb-3">
			<div class="col-md-6">
			  <label for="title" class="form-label">Title</label>
			  <input type="text" name="title" id="title" class="form-control" required>
			</div>

			<div class="col-md-6">
			  <label for="category" class="form-label">Category</label>
			  <input type="text" name="category" id="category" class="form-control" required>
			</div>
		  </div>

		  <div class="row mb-3">
			<div class="col-md-6">
			  <label for="author" class="form-label">Author</label>
			  <input type="text" name="author" id="author" class="form-control" required>
			</div>

			<div class="col-md-6">
			  <label for="genre" class="form-label">Genre</label>
			  <input type="text" name="genre" id="genre" class="form-control" required>
			</div>
		  </div>

		  <div class="row mb-3">
			<div class="col-md-4">
			  <label for="shelf" class="form-label">Shelf Number</label>
			  <input type="text" name="shelf" id="shelf" class="form-control" required>
			</div>

			<div class="col-md-4">
			  <label for="price" class="form-label">Price ($)</label>
			  <input type="number" step="0.01" name="price" id="price" class="form-control" required>
			</div>

			<div class="col-md-4">
			  <label for="year" class="form-label">Year</label>
			  <input type="number" name="year" id="year" class="form-control" required>
			</div>
		  </div>

		  <div class="mb-3">
			<label for="condition" class="form-label">Condition</label>
			<select name="condition" id="condition" class="form-select" required>
			  <option value="">Choose...</option>
			  <option value="New">New</option>
			  <option value="Good">Good</option>
			  <option value="Fair">Fair</option>
			  <option value="Used">Used</option>
			  <option value="Damaged">Damaged</option>
			</select>
		  </div>

		  <button type="submit" class="btn btn-primary">Add Book</button>
		</form>
	</div>
</div>