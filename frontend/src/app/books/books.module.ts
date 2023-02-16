import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { BooksRoutingModule } from './books-routing.module';
import { BooksComponent } from './books.component';
import { BooksListComponent } from './books-list/books-list.component';
import { ComponentsModule } from '../shared/components/components.module';
import { BookAddComponent } from './book-add/book-add.component';

@NgModule({
  declarations: [BooksComponent, BooksListComponent, BookAddComponent],
  exports: [BooksComponent],
  imports: [CommonModule, BooksRoutingModule, ComponentsModule],
})
export class BooksModule {}
