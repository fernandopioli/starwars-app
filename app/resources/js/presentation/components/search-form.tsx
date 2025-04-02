import React from 'react';
import '../../../css/search-form.css';

interface SearchFormProps {
  searchType: 'people' | 'movies';
  searchTerm: string;
  onTypeChange: (type: 'people' | 'movies') => void;
  onSearchTermChange: (term: string) => void;
  onSubmit: () => void;
  isLoading: boolean;
}

const SearchForm: React.FC<SearchFormProps> = ({
  searchType,
  searchTerm,
  onTypeChange,
  onSearchTermChange,
  onSubmit,
  isLoading
}) => {
  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    onSearchTermChange(e.target.value);
  };
  
  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (searchTerm.trim()) {
      onSubmit();
    }
  };
  
  const handleTypeChange = (type: 'people' | 'movies') => {
    onTypeChange(type);
  };
  
  const isButtonDisabled = isLoading || !searchTerm.trim();
  
  return (
    <div className="search-form-container">
      <h2>What are you searching for?</h2>
      
      <div className="search-type-selector">
        <label className={`search-type-option ${searchType === 'people' ? 'selected' : ''}`}>
          <input
            type="radio"
            name="searchType"
            value="people"
            checked={searchType === 'people'}
            onChange={() => handleTypeChange('people')}
          />
          <span className="radio-circle"></span>
          People
        </label>
        
        <label className={`search-type-option ${searchType === 'movies' ? 'selected' : ''}`}>
          <input
            type="radio"
            name="searchType"
            value="movies"
            checked={searchType === 'movies'}
            onChange={() => handleTypeChange('movies')}
          />
          <span className="radio-circle"></span>
          Movies
        </label>
      </div>
      
      <form onSubmit={handleSubmit}>
        <div className="search-input-container">
          <input
            type="text"
            className="search-input"
            placeholder={searchType === 'people' ? 
              "e.g. Chewbacca, Yoda, Boba Fett" : 
              "e.g. A New Hope, Empire Strikes Back"}
            value={searchTerm}
            onChange={handleInputChange}
            disabled={isLoading}
          />
        </div>
        
        <button
          type="submit"
          className="search-button"
          disabled={isButtonDisabled}
        >
          {isLoading ? 'SEARCHING...' : 'SEARCH'}
        </button>
      </form>
    </div>
  );
};

export default SearchForm;