import React, { useState } from 'react';

import { Film, Person } from '@/domain/entities';
import { getFilmsUseCase } from '@/main/dependencies-factory';
import { getPeopleUseCase } from '@/main/dependencies-factory';
import SearchForm from '@/presentation/components/search-form';
import SearchResults from '@/presentation/components/search-results';
import '@/css/search-page.css';

const SearchPage: React.FC = () => {
    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [error, setError] = useState<string | null>(null);

  const [hasSearched, setHasSearched] = useState<boolean>(false);
  const [searchType, setSearchType] = useState<'people' | 'movies'>('people');
  const [searchTerm, setSearchTerm] = useState<string>('');
  
  const [peopleResults, setPeopleResults] = useState<Person[]>([]);
  const [filmsResults, setFilmsResults] = useState<Film[]>([]);
  

  const handleSearch = async (term: string, type: 'people' | 'movies') => {
    if (!term.trim()) {
      setPeopleResults([]);
      setFilmsResults([]);
      setHasSearched(true);
      return;
    }
    
    setPeopleResults([]);
    setFilmsResults([]);
    
    setIsLoading(true);
    setError(null);
    setHasSearched(true);
    
    try {
      if (type === 'people') {
        const result = await getPeopleUseCase.execute(term);
        setPeopleResults(result.data);
      } else {
        const result = await getFilmsUseCase.execute(term);
        setFilmsResults(result.data);
      }
    } catch (e) {
      setError(e instanceof Error ? e.message : 'Error ');
    } finally {
      setIsLoading(false);
    }
  };
  
  const handleTypeChange = (type: 'people' | 'movies') => {
    setSearchType(type);
    if (searchTerm && hasSearched) {
      handleSearch(searchTerm, type);
    }
  };
  
  const handleSearchTermChange = (term: string) => {
    setSearchTerm(term);
  };
  
  const handleSubmit = () => {
    handleSearch(searchTerm, searchType);
  };
  
  return (
    <div className="search-page">
      <div className="search-page-content">
        <div className="search-panel">
          <SearchForm 
            searchType={searchType}
            searchTerm={searchTerm}
            onTypeChange={handleTypeChange}
            onSearchTermChange={handleSearchTermChange}
            onSubmit={handleSubmit}
            isLoading={isLoading}
          />
        </div>
        
        <div className="results-panel">
          <SearchResults 
            searchType={searchType}
            peopleResults={peopleResults}
            filmsResults={filmsResults}
            isLoading={isLoading}
            error={error}
            hasSearched={hasSearched}
          />
        </div>
      </div>
    </div>
  );
};

export default SearchPage;