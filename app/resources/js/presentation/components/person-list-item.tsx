import React from 'react';
import { Link } from 'react-router-dom';
import { Person } from '@/domain/entities';
import '../../../css/list-item.css';

interface PersonListItemProps {
  person: Person;
}

const PersonListItem: React.FC<PersonListItemProps> = ({ person }) => {
  return (
    <div className="list-item">
      <div className="list-item-details">
        <h3 className="list-item-title">{person.name}</h3>
      </div>
      <div className="list-item-actions">
        <Link to={`/people/${person.id}`} className="see-details-button">
          SEE DETAILS
        </Link>
      </div>
    </div>
  );
};

export default PersonListItem;