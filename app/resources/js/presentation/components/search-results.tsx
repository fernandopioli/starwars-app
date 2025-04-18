import React from 'react';
import { Film, Person } from '@/domain/entities';
import '../../../css/search-results.css';
import PersonListItem from './person-list-item';
import FilmListItem from './film-list-item';

interface SearchResultsProps {
  searchType: 'people' | 'movies';
  peopleResults: Person[];
  filmsResults: Film[];
  isLoading: boolean;
  error: string | null;
  hasSearched: boolean;
}

const SearchResults: React.FC<SearchResultsProps> = ({
  searchType,
  peopleResults,
  filmsResults,
  isLoading,
  error,
  hasSearched,
}) => {
  const defaultMessage = (
    <div className="empty-state">
      <p>There are zero matches. <br />Use the form to search for {searchType === 'people' ? 'People' : 'Movies'}.</p>
    </div>
  );
  
  const renderEmptyState = () => {
    if (!hasSearched) {
      return defaultMessage;
    }
    
    if (error) {    
      return (
        <div className="empty-state error">
          <p>{error}</p>
        </div>
      );
    }
    
    if (isLoading) {
      return (
        <div className="empty-state">
          <p>Searching...</p>
        </div>
      );
    }

    if ((searchType === 'people' && peopleResults.length === 0) ||
        (searchType === 'movies' && filmsResults.length === 0) ||
        !searchType) {
      return defaultMessage;
    }
    
    return null;
  };
  
  const renderPeopleResults = () => {
    if (searchType !== 'people' || peopleResults.length === 0 || isLoading) {
      return null;
    }
    
    return (
      <div className="results-list">
        {peopleResults.map(person => (
          <PersonListItem 
            key={person.id} 
            person={person}
          />
        ))}
      </div>
    );
  };
  
  const renderFilmsResults = () => {
    if (searchType !== 'movies' || filmsResults.length === 0 || isLoading) {
      return null;
    }
    
    return (
      <div className="results-list">
        {filmsResults.map(film => (
          <FilmListItem 
            key={film.id} 
            film={film}
          />
        ))}
      </div>
    );
  };
  
  return (
    <div className="search-results-container">
      <h2>Results</h2>
      
      <div className="search-results-content">
        {renderEmptyState()}
        {!isLoading && (
          <>
            {renderPeopleResults()}
            {renderFilmsResults()}
          </>
        )}
      </div>
    </div>
  );
};

export default SearchResults;