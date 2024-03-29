import { Component } from '@angular/core';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss'],
})
export class HeaderComponent {
  routes: Array<{ path: string; label: string }> = [
    { path: '/', label: 'Accueil' },
    { path: '/books/new', label: 'Ajout de livre' },
  ];
}
