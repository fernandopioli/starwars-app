import React from 'react';
import { Link } from 'react-router-dom';
import { Film } from '@/domain/entities';
import '@/css/list-item.css';

interface FilmListItemProps {
  film: Film;
}

const FilmListItem: React.FC<FilmListItemProps> = ({ film }) => {
  return (
    <div className="list-item">
      <div className="list-item-details">
        <h3 className="list-item-title">{film.title}</h3>
      </div>
      <div className="list-item-actions">
        <Link to={`/films/${film.id}`} className="see-details-button">
          SEE DETAILS
        </Link>
      </div>
    </div>
  );
};

export default FilmListItem;